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

    // PhonePe PG v1 uses X-VERIFY signing. This controller implements pay + status with signatures.

    private function build_headers(string $base64Payload, string $path): array
    {
        $merchantId = $this->config->item('phonepe_merchant_id');
        $saltKey    = $this->config->item('phonepe_salt_key');
        $saltIndex  = $this->config->item('phonepe_salt_index');

        $stringToSign = $base64Payload . $path . $saltKey;
        $sha256 = hash('sha256', $stringToSign);
        $xVerify = $sha256 . '###' . $saltIndex;

        return [
            self::HEADER_JSON,
            'X-VERIFY: ' . $xVerify,
            'X-MERCHANT-ID: ' . $merchantId,
        ];
    }

    // Create payment order (PG PAY)
    public function create_payment()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?: $this->input->post();

        $merchantId = $this->config->item('phonepe_merchant_id');
        $baseUrl    = rtrim($this->config->item('phonepe_pg_base_url'), '/');
        $path       = '/pg/v1/pay';
        $url        = $baseUrl . $path;

        $merchantTransactionId = $data['merchantOrderId'] ?? 'KM' . time() . rand(1000, 9999);
        $amount = isset($data['amount']) ? (float)$data['amount'] : 51.0;
        $amountPaise = intval(round($amount * 100));

        // Prefer a phone number if available for merchantUserId
        $merchantUserId = isset($data['phone']) ? preg_replace('/\D+/', '', $data['phone']) : 'KMUSER' . date('Ymd');

        $payload = [
            'merchantId'              => $merchantId,
            'merchantTransactionId'   => $merchantTransactionId,
            'merchantUserId'          => $merchantUserId,
            'amount'                  => $amountPaise,
            'redirectUrl'             => site_url('payment/payment_confirmation?orderId=' . urlencode($merchantTransactionId)),
            'redirectMode'            => 'GET',
            'callbackUrl'             => site_url('payment/check_status/' . rawurlencode($merchantTransactionId)),
            'paymentInstrument'       => [ 'type' => 'PAY_PAGE' ],
        ];

        $payloadJson  = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $base64       = base64_encode($payloadJson);
        $requestBody  = json_encode(['request' => $base64]);
        $headers      = $this->build_headers($base64, $path);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);

        header(self::HEADER_JSON);
        if ($response === false) {
            echo json_encode([
                'success' => false,
                'code' => 'PAYMENT_ERROR',
                'message' => 'Payment creation failed',
                'curl_error' => $error,
                'curl_info' => $info
            ]);
            return;
        }

        echo $response;
    }

    // Check order status (PG STATUS)
    public function order_status($orderId = '')
    {
        if (!$orderId) {
            $orderId = $this->input->get('orderId');
        }
        if (!$orderId) {
            header(self::HEADER_JSON);
            echo json_encode(['success' => false, 'message' => 'orderId is required']);
            return;
        }

        $merchantId = $this->config->item('phonepe_merchant_id');
        $baseUrl    = rtrim($this->config->item('phonepe_pg_base_url'), '/');
        $path       = '/pg/v1/status/' . rawurlencode($merchantId) . '/' . rawurlencode($orderId);
        $url        = $baseUrl . $path;

        // For status, the request body is empty string when signing
        $base64 = '';
        $headers = $this->build_headers($base64, $path);

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
            echo json_encode([
                'success' => false,
                'code' => 'STATUS_ERROR',
                'message' => 'Order status check failed',
                'curl_error' => $error,
                'curl_info' => $info
            ]);
            return;
        }

        echo $response;
    }

    // Diagnostic endpoint - simple health
    public function diagnostic()
    {
        header(self::HEADER_JSON);
        echo json_encode(['status' => 'ok', 'timestamp' => time()]);
    }
}