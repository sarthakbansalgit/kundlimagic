<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Phonepe extends CI_Controller
{
    const HEADER_JSON = 'Content-Type: application/json';
    const HEADER_FORM = 'Content-Type: application/x-www-form-urlencoded';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        log_message('debug', 'Phonepe controller initialized');
    }

    // Diagnostic endpoint to test token acquisition
    public function token()
    {
        $result = $this->token_internal();
        header(self::HEADER_JSON);
        echo $result['body'] ?? json_encode(['status' => 'error', 'message' => 'no response']);
    }

    // Internal method to fetch access token (legacy - not used in PG X-VERIFY flow)
    private function token_internal()
    {
        $client_id = $this->config->item('client_id');
        $client_secret = $this->config->item('client_secret');
        $client_version = $this->config->item('client_version');
        $url = $this->config->item('token_url');

        $post_data = http_build_query([
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'client_version' => $client_version,
            'grant_type' => 'client_credentials'
        ]);

        $basic_auth = base64_encode($client_id . ':' . $client_secret);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            self::HEADER_FORM,
            'Authorization: Basic ' . $basic_auth
        ]);
        $body = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);

        return [
            'http_code' => $info['http_code'] ?? 0,
            'body' => $body,
            'curl_error' => $error,
            'curl_info' => $info
        ];
    }

    // Compute X-VERIFY header value for PG APIs
    private function compute_x_verify($base64_payload_or_empty, $path)
    {
        $salt_key = $this->config->item('phonepe_salt_key');
        $salt_index = $this->config->item('phonepe_salt_index');
        // For /pg/v1/pay => sha256(base64payload + path + saltKey)
        // For /pg/v1/status => sha256(path + saltKey)
        $to_hash = $base64_payload_or_empty === ''
            ? ($path . $salt_key)
            : ($base64_payload_or_empty . $path . $salt_key);
        $sha256 = hash('sha256', $to_hash);
        return $sha256 . '###' . $salt_index;
    }

    // Create payment order using PhonePe PG X-VERIFY flow
    public function create_payment()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?: $this->input->post();

        $merchantTransactionId = $data['merchantOrderId'] ?? ('KM' . time() . rand(1000, 9999));
        $amount_rupees = isset($data['amount']) ? (float)$data['amount'] : 51.0;
        $amount_paisa = intval(round($amount_rupees * 100));

        $merchantId = $this->config->item('phonepe_merchant_id');
        $base = rtrim($this->config->item('phonepe_pg_base'), '/');

        $form = $this->session->userdata('kundli_form_data') ?: [];
        $mobileNumber = $form['phone'] ?? ($data['phone'] ?? null);
        $merchantUserId = $mobileNumber ?: ('U' . substr($merchantTransactionId, -6));

        $redirectUrl = site_url('payment/payment_confirmation?orderId=' . urlencode($merchantTransactionId));
        $callbackUrl = $redirectUrl; // same endpoint can handle server callback if needed

        $pay_body = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $merchantTransactionId,
            'merchantUserId' => $merchantUserId,
            'amount' => $amount_paisa,
            'redirectUrl' => $redirectUrl,
            'redirectMode' => 'GET',
            'callbackUrl' => $callbackUrl,
            'mobileNumber' => $mobileNumber,
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $json = json_encode($pay_body, JSON_UNESCAPED_SLASHES);
        $base64 = base64_encode($json);
        $path = '/pg/v1/pay';
        $url = $base . $path;
        $x_verify = $this->compute_x_verify($base64, $path);

        $headers = [
            self::HEADER_JSON,
            'X-VERIFY: ' . $x_verify,
            'X-MERCHANT-ID: ' . $merchantId
        ];

        $post_body = json_encode(['request' => $base64]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);

        header(self::HEADER_JSON);
        if ($response === false) {
            echo json_encode(['status' => 'error', 'message' => 'Payment creation failed', 'curl_error' => $error, 'curl_info' => $info]);
            return;
        }

        echo $response;
    }

    // Check order status
    public function order_status($orderId = '')
    {
        if (!$orderId) {
            $orderId = $this->input->get('orderId');
        }
        if (!$orderId) {
            header(self::HEADER_JSON);
            echo json_encode(['status' => 'error', 'message' => 'orderId is required']);
            return;
        }

        $merchantId = $this->config->item('phonepe_merchant_id');
        $base = rtrim($this->config->item('phonepe_pg_base'), '/');
        $path = '/pg/v1/status/' . rawurlencode($merchantId) . '/' . rawurlencode($orderId);
        $url = $base . $path;
        $x_verify = $this->compute_x_verify('', $path);

        $headers = [
            self::HEADER_JSON,
            'X-VERIFY: ' . $x_verify,
            'X-MERCHANT-ID: ' . $merchantId
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);

        header(self::HEADER_JSON);
        if ($response === false) {
            echo json_encode(['status' => 'error', 'message' => 'Order status check failed', 'curl_error' => $error, 'curl_info' => $info]);
            return;
        }

        echo $response;
    }

    // Diagnostic endpoint
    public function diagnostic()
    {
        $this->token();
    }
}