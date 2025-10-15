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

    // Internal method to fetch access token
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

        // Create payment order
    public function create_payment()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?: $this->input->post();

        $merchantOrderId = $data['merchantOrderId'] ?? 'KM' . time() . rand(1000, 9999);
        $amount = isset($data['amount']) ? (float)$data['amount'] : 51.0;

        // Get token
        $token_result = $this->token_internal();
        $token_data = json_decode($token_result['body'], true);
        if (empty($token_data['access_token'])) {
            header(self::HEADER_JSON);
            echo json_encode(['status' => 'error', 'message' => 'Failed to obtain access token', 'token_result' => $token_result]);
            return;
        }

        $access_token = $token_data['access_token'];
        $payment_url = $this->config->item('payment_url');

        $payload = [
            'merchantOrderId' => $merchantOrderId,
            'amount' => intval($amount * 100), // Amount in paisa
            'paymentFlow' => [
                'type' => 'PG_CHECKOUT',
                'merchantUrls' => [
                    'redirectUrl' => site_url('payment/payment_confirmation?orderId=' . urlencode($merchantOrderId)),
                    'cancelUrl' => site_url('payment/payment_cancel?orderId=' . urlencode($merchantOrderId)),
                    'failureUrl' => site_url('payment/payment_failure?orderId=' . urlencode($merchantOrderId))
                ]
            ]
        ];

        $json_payload = json_encode($payload);
        $ch = curl_init($payment_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            self::HEADER_JSON,
            'Authorization: Bearer ' . $access_token
        ]);
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

        // Get token
        $token_result = $this->token_internal();
        $token_data = json_decode($token_result['body'], true);
        if (empty($token_data['access_token'])) {
            header(self::HEADER_JSON);
            echo json_encode(['status' => 'error', 'message' => 'Failed to obtain access token', 'token_result' => $token_result]);
            return;
        }

        $access_token = $token_data['access_token'];
        $base_url = $this->config->item('payment_url');
        $status_url = rtrim($base_url, '/') . '/order/' . rawurlencode($orderId) . '/status?details=false';

        $ch = curl_init($status_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            self::HEADER_JSON,
            'Authorization: Bearer ' . $access_token
        ]);
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