<?php
defined('BASEPATH') || exit('No direct script access allowed');

class PhonepeClean extends CI_Controller
{
    const HEADER_JSON = 'Content-Type: application/json';
    const HEADER_FORM = 'Content-Type: application/x-www-form-urlencoded';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function token()
    {
        $client_id = $this->config->item('client_id');
        $client_secret = $this->config->item('client_secret');
        $client_version = $this->config->item('client_version');
        $url = $this->config->item('token_url');

        $attempts = [];

        // Basic auth attempt
        $post_basic = http_build_query(['grant_type' => 'client_credentials']);
        $basic = base64_encode($client_id . ':' . $client_secret);
        $headers_basic = [self::HEADER_FORM, 'Authorization: Basic ' . $basic];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_basic);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_basic);
        $resp_basic = curl_exec($ch);
        $info_basic = curl_getinfo($ch);
        $err_basic = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);
        $attempts[] = ['method' => 'basic', 'response' => $resp_basic, 'info' => $info_basic, 'error' => $err_basic];

        $dec_basic = json_decode($resp_basic, true);
        if (!empty($dec_basic['access_token'])) {
            header(self::HEADER_JSON);
            echo $resp_basic;
            return;
        }

        // Body attempt
        $post_body = http_build_query([
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'client_version' => $client_version,
            'grant_type' => 'client_credentials'
        ]);
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_POST, true);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [self::HEADER_FORM]);
        $resp_body = curl_exec($ch2);
        $info_body = curl_getinfo($ch2);
        $err_body = curl_errno($ch2) ? curl_error($ch2) : null;
        curl_close($ch2);
        $attempts[] = ['method' => 'body', 'response' => $resp_body, 'info' => $info_body, 'error' => $err_body];

        $dec_body = json_decode($resp_body, true);
        if (!empty($dec_body['access_token'])) {
            header(self::HEADER_JSON);
            echo $resp_body;
            return;
        }

        header(self::HEADER_JSON);
        echo json_encode(['status' => 'error', 'message' => 'Token failed', 'attempts' => $attempts], JSON_PRETTY_PRINT);
    }

    private function tokenInternal()
    {
        ob_start();
        $this->token();
        $out = ob_get_clean();
        return $out ?: json_encode(['status' => 'error', 'message' => 'no token output']);
    }

    public function create_payment()
    {
        $raw = file_get_contents('php://input');
        $body = json_decode($raw, true) ?: $this->input->post();
        $merchantOrderId = $body['merchantOrderId'] ?? 'KM' . time() . rand(1000, 9999);
        $amount = isset($body['amount']) ? (float)$body['amount'] : 51.0;

    $token_json = $this->tokenInternal();
        $dec = json_decode($token_json, true);
        if (empty($dec['access_token'])) {
            header(self::HEADER_JSON);
            echo json_encode(['status' => 'error', 'message' => 'no token', 'token' => $dec], JSON_PRETTY_PRINT);
            return;
        }

        $access_token = $dec['access_token'];
        $create_url = $this->config->item('payment_url');
        $payload = json_encode(['merchantOrderId' => $merchantOrderId, 'amount' => intval($amount * 100), 'paymentFlow' => ['type' => 'PG_CHECKOUT']]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $create_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [self::HEADER_JSON, 'Authorization: Bearer ' . $access_token]);
        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);

        header(self::HEADER_JSON);
        if ($resp === false) {
            echo json_encode(['status' => 'error', 'curl_error' => $err, 'curl_info' => $info], JSON_PRETTY_PRINT);
            return;
        }
        echo $resp;
    }

    public function diagnostic()
    {
        $this->token();
    }

    public function order_status($orderId = '')
    {
        if (!$orderId) {
            $orderId = $this->input->get('orderId');
        }
        if (!$orderId) {
            header(self::HEADER_JSON);
            echo json_encode(['status' => 'error', 'message' => 'orderId required']);
            return;
        }

    $token_json = $this->tokenInternal();
        $dec = json_decode($token_json, true);
        if (empty($dec['access_token'])) {
            header(self::HEADER_JSON);
            echo json_encode(['status' => 'error', 'message' => 'no token', 'token' => $dec]);
            return;
        }

        $access_token = $dec['access_token'];
        $status_base = $this->config->item('payment_url');
        $url = rtrim($status_base, '/') . '/order/' . rawurlencode($orderId) . '/status?details=false';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [self::HEADER_JSON, 'Authorization: Bearer ' . $access_token]);
        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);
        header(self::HEADER_JSON);
        if ($resp === false) {
            echo json_encode(['status' => 'error', 'curl_error' => $err, 'curl_info' => $info], JSON_PRETTY_PRINT);
            return;
        }
        echo $resp;
    }
}
