<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->model('User_model');
        log_message('debug', 'Payment controller initialized');
    }

    /**
     * 🔹 Step 1: Initiate Payment — called from frontend
     * Example POST JSON: { "amount": 100, "merchantOrderId": "KM123456" }
     */
    public function initiate_payment()
    {
        // Increase execution time for API calls
        ini_set('max_execution_time', 120); // Increase to 2 minutes
        
        // Read raw JSON or form data
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        if (!$data) {
            $data = $this->input->post();
        }

        // Validate required fields
        $required_fields = ['name', 'phone', 'gender', 'dob', 'tob', 'pob', 'language', 'kundli_type'];
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Please fill all required fields: ' . implode(', ', $missing_fields)
            ]);
            return;
        }

        // Validate and set defaults
        $amount = isset($data['amount']) ? (float)$data['amount'] : 51.00;
        
        // Set amount based on kundli_type if provided
        if (isset($data['kundli_type'])) {
            $amount = ($data['kundli_type'] === 'detailed') ? 101.00 : 51.00;
        }
        
        $merchantOrderId = !empty($data['merchantOrderId'])
            ? $data['merchantOrderId']
            : 'KM' . time() . rand(1000, 9999);

        // Store form data in session for later use in confirmation
        $this->session->set_userdata('kundli_form_data', $data);
        $this->session->set_userdata('merchant_order_id', $merchantOrderId);

        // Direct PhonePe PG v1 call (avoid internal HTTP dependency)
        $merchantId = $this->config->item('phonepe_merchant_id');
        $baseUrl    = rtrim($this->config->item('phonepe_pg_base_url'), '/');
        $saltKey    = $this->config->item('phonepe_salt_key');
        $saltIndex  = $this->config->item('phonepe_salt_index');
        $path       = '/pg/v1/pay';
        $url        = $baseUrl . $path;

        $merchantUserId = isset($data['phone']) ? preg_replace('/\D+/', '', $data['phone']) : 'KMUSER' . date('Ymd');
        $amountPaise = intval(round($amount * 100));

        $pp_payload = [
            'merchantId'              => $merchantId,
            'merchantTransactionId'   => $merchantOrderId,
            'merchantUserId'          => $merchantUserId,
            'amount'                  => $amountPaise,
            'redirectUrl'             => site_url('payment/payment_confirmation?orderId=' . urlencode($merchantOrderId)),
            'redirectMode'            => 'GET',
            'callbackUrl'             => site_url('payment/check_status/' . rawurlencode($merchantOrderId)),
            'paymentInstrument'       => [ 'type' => 'PAY_PAGE' ],
        ];

        $payloadJson = json_encode($pp_payload, JSON_UNESCAPED_SLASHES);
        $base64      = base64_encode($payloadJson);
        $requestBody = json_encode(['request' => $base64]);
        $xverify     = hash('sha256', $base64 . $path . $saltKey) . '###' . $saltIndex;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-VERIFY: ' . $xverify,
            'X-MERCHANT-ID: ' . $merchantId,
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        header('Content-Type: application/json');

        if ($error || $http_code !== 200) {
            echo json_encode([
                'success' => false,
                'code'    => 'PAYMENT_ERROR',
                'message' => 'Unable to initiate payment. Please try again shortly.'
            ]);
            return;
        }

        echo $response;
    }

    /**
     * 🔹 Step 2: Payment confirmation (success redirect)
     * Redirect from PhonePe after payment success
     */
    public function payment_confirmation()
    {
        $orderId = $this->input->get('orderId');
        if (!$orderId) {
            show_error('Order ID is required');
            return;
        }

        // Check if order ID matches the stored one
        $stored_order_id = $this->session->userdata('merchant_order_id');
        if ($stored_order_id !== $orderId) {
            show_error('Invalid order ID');
            return;
        }

        // Get stored form data
        $form_data = $this->session->userdata('kundli_form_data');
        if (!$form_data) {
            show_error('Form data not found');
            return;
        }

        // Verify payment status with PhonePe before proceeding
        $status_url = site_url('phonepe/order_status?orderId=' . urlencode($orderId));
        $ch = curl_init($status_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $status_response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $status_err = curl_error($ch);
        curl_close($ch);

        if ($status_err || $status_code !== 200) {
            $this->session->set_flashdata('error', 'Unable to verify payment at the moment. Please contact support.');
            redirect('payment/payment_failure?orderId=' . urlencode($orderId));
            return;
        }

        $status_json = json_decode($status_response, true);
        $is_success = false;
        if (is_array($status_json)) {
            // Accept either PhonePe style success true + PAYMENT_SUCCESS, or legacy structures
            if ((isset($status_json['success']) && $status_json['success'] === true && isset($status_json['code']) && in_array($status_json['code'], ['PAYMENT_SUCCESS', 'SUCCESS'], true))
                || (isset($status_json['data']['state']) && in_array($status_json['data']['state'], ['COMPLETED', 'SUCCESS'], true))
                || (isset($status_json['data']['responseCode']) && $status_json['data']['responseCode'] === 'SUCCESS')) {
                $is_success = true;
            }
        }

        if (!$is_success) {
            $this->session->set_flashdata('error', 'Payment not completed.');
            redirect('payment/payment_failure?orderId=' . urlencode($orderId));
            return;
        }

        // Check if user is logged in
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            // Try to find existing user by phone first
            $existing = null;
            if (!empty($form_data['phone'])) {
                $existing = $this->User_model->get_user_by_phone($form_data['phone']);
            }

            if ($existing && isset($existing['id'])) {
                $user = $this->User_model->get_user($existing['id']);
                if ($user) {
                    $this->session->set_userdata([
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'logged_in' => true
                    ]);
                    $user_id = $user->id;
                } else {
                    show_error('Failed to load existing user');
                    return;
                }
            } else {
                // Create user account
                $user_data = [
                    'name'     => $form_data['name'],
                    'email'    => $form_data['email'] ?? null,
                    // Provide both fields so model can populate correctly
                    'ph_no'    => $form_data['phone'] ?? null,
                    'whatsapp' => $form_data['phone'] ?? null,
                ];

                $new_user_id = $this->User_model->create_user_from_kundli($user_data);
                if (!$new_user_id) {
                    show_error('Failed to create user account');
                    return;
                }

                // Log the user in
                $user = $this->User_model->get_user($new_user_id);
                if ($user) {
                    $session_data = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'logged_in' => true
                    ];
                    $this->session->set_userdata($session_data);
                    $user_id = $user->id;
                } else {
                    show_error('Failed to log in user');
                    return;
                }
            }
        }

        // Generate kundli record
        $kundli_data = [
            'user_id' => $user_id,
            'name' => $form_data['name'],
            'birth_date' => $form_data['dob'],
            'birth_time' => $form_data['tob'],
            'birth_place' => $form_data['pob'],
            'kundli_data' => json_encode([
                'gender' => $form_data['gender'] ?? null,
                'language' => $form_data['language'] ?? null,
                'kundli_type' => $form_data['kundli_type'] ?? 'basic',
                'lat' => $form_data['lat'] ?? null,
                'long' => $form_data['long'] ?? null
            ])
        ];

        $kundli_id = $this->User_model->save_kundli($kundli_data);
        if (!$kundli_id) {
            show_error('Failed to generate kundli');
            return;
        }

        // Attempt to generate a simple PDF and store locally for dashboard link
        try {
            $uploadsDir = FCPATH . 'uploads/kundlis/';
            if (!is_dir($uploadsDir)) {
                @mkdir($uploadsDir, 0755, true);
            }

            // Build very simple HTML summary
            $html = '<html><head><meta charset="utf-8"><style>body{font-family: DejaVu Sans, sans-serif;padding:24px;}h1{color:#ff7010;}table{width:100%;border-collapse:collapse;margin-top:16px}td{border:1px solid #ddd;padding:8px}</style></head><body>';
            $html .= '<h1>Kundli Summary</h1>';
            $html .= '<table>';
            $html .= '<tr><td><strong>Name</strong></td><td>' . htmlspecialchars($form_data['name']) . '</td></tr>';
            $html .= '<tr><td><strong>Birth Date</strong></td><td>' . htmlspecialchars($form_data['dob']) . '</td></tr>';
            $html .= '<tr><td><strong>Birth Time</strong></td><td>' . htmlspecialchars($form_data['tob']) . '</td></tr>';
            $html .= '<tr><td><strong>Birth Place</strong></td><td>' . htmlspecialchars($form_data['pob']) . '</td></tr>';
            $html .= '<tr><td><strong>Gender</strong></td><td>' . htmlspecialchars($form_data['gender'] ?? '') . '</td></tr>';
            $html .= '<tr><td><strong>Language</strong></td><td>' . htmlspecialchars($form_data['language'] ?? '') . '</td></tr>';
            $html .= '<tr><td><strong>Type</strong></td><td>' . htmlspecialchars($form_data['kundli_type'] ?? 'basic') . '</td></tr>';
            $html .= '</table>';
            $html .= '<p style="margin-top:16px;color:#666">This is a placeholder PDF. Replace with real kundli rendering.</p>';
            $html .= '</body></html>';

            // Render PDF using Dompdf directly
            require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();

            $relativePath = 'uploads/kundlis/kundli_' . $kundli_id . '.pdf';
            $filePath = FCPATH . $relativePath;
            file_put_contents($filePath, $output);

            // Update kundli record with local PDF path
            $this->User_model->update_kundli($kundli_id, ['local_pdf_path' => $relativePath]);
        } catch (\Throwable $e) {
            // If PDF generation fails, continue without blocking flow
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
        }

        // Clear session data
        $this->session->unset_userdata(['kundli_form_data', 'merchant_order_id']);

        // Redirect to view kundli
        redirect('dashboard/view_kundli/' . $kundli_id);
    }

    /**
     * 🔹 Step 3: Payment cancel callback
     */
    public function payment_cancel()
    {
        $orderId = $this->input->get('orderId') ?? 'N/A';
        $data['orderId'] = $orderId;
        $data['status']  = 'cancelled';
        $this->load->view('payment_cancel', $data);
    }

    /**
     * 🔹 Step 4: Payment failure callback
     */
    public function payment_failure()
    {
        $orderId = $this->input->get('orderId') ?? 'N/A';
        $data['orderId'] = $orderId;
        $data['status']  = 'failed';
        $this->load->view('payment_failure', $data);
    }

    /**
     * 🔹 Step 5: Check Payment Status
     * This will internally call PhonePe order_status
     */
    public function check_status($orderId = '')
    {
        // Increase execution time for API calls
        ini_set('max_execution_time', 60);
        
        if (empty($orderId)) {
            $orderId = $this->input->get('orderId');
        }

        if (empty($orderId)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Order ID required']);
            return;
        }

        $phonepe_url = site_url('phonepe/order_status?orderId=' . urlencode($orderId));

        $ch = curl_init($phonepe_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 second timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10 second connection timeout
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        header('Content-Type: application/json');

        if ($error) {
            echo json_encode(['status' => 'error', 'message' => 'Payment service temporarily unavailable. Please try again later.']);
            return;
        }

        if ($http_code !== 200) {
            echo json_encode(['status' => 'error', 'message' => 'Payment service error. Please try again later.']);
            return;
        }

        echo $response;
    }
}
