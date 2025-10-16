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
        
        // Also store in database as backup
        $this->load->model('frontend/Contactmodel');
        $db_data = $data;
        $db_data['merchantOrderId'] = $merchantOrderId;
        $db_data['payment_status'] = 'PENDING';
        $db_data['amount'] = $amount;
        $this->Contactmodel->generate_kundli($db_data);

        // Call PhonePe controller (internal API)
        $phonepe_url = site_url('phonepe/create_payment');

        $payload = json_encode([
            'merchantOrderId' => $merchantOrderId,
            'amount'          => $amount
        ]);

        $ch = curl_init($phonepe_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 second timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10 second connection timeout
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        header('Content-Type: application/json');

        if ($error) {
            log_message('error', 'PhonePe API CURL Error: ' . $error);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Payment service temporarily unavailable. Please try again later.',
                'error_code' => 'CURL_ERROR'
            ]);
            return;
        }

        if ($http_code !== 200) {
            log_message('error', 'PhonePe API HTTP Error: ' . $http_code . ' Response: ' . $response);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Payment service error. Please try again later.',
                'error_code' => 'HTTP_' . $http_code
            ]);
            return;
        }

        // Log successful response for debugging
        log_message('debug', 'PhonePe API Response: ' . $response);

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
            log_message('error', 'Payment confirmation called without orderId');
            show_error('Order ID is required for payment confirmation');
            return;
        }

        // Check if order ID matches the stored one
        $stored_order_id = $this->session->userdata('merchant_order_id');
        if ($stored_order_id !== $orderId) {
            log_message('error', 'Order ID mismatch. Expected: ' . $stored_order_id . ', Got: ' . $orderId);
            
            // Try to find the order in database as fallback
            $this->load->model('frontend/Contactmodel');
            $existing_order = $this->Contactmodel->get_kundli_by_order_id($orderId);
            if (!$existing_order) {
                show_error('Invalid or expired order ID');
                return;
            }
            
            // If order exists in DB but not in session, continue with processing
            log_message('info', 'Found order in database, continuing with payment confirmation');
        }

        // Verify payment status with PhonePe before processing
        $payment_status = $this->verify_payment_status($orderId);
        if ($payment_status !== 'SUCCESS') {
            log_message('error', 'Payment verification failed for order: ' . $orderId . ', status: ' . $payment_status);
            show_error('Payment verification failed. Please contact support if money was debited.');
            return;
        }

        // Get stored form data
        $form_data = $this->session->userdata('kundli_form_data');
        if (!$form_data) {
            log_message('error', 'Form data not found in session for order: ' . $orderId);
            
            // Try to get from database
            if (isset($existing_order)) {
                $form_data = $existing_order;
                log_message('info', 'Retrieved form data from database');
            } else {
                show_error('Form data not found. Please try generating your Kundli again.');
                return;
            }
        }

        // Check if user is logged in
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            // Check if user already exists by phone number
            $existing_user = $this->User_model->get_user_by_phone($form_data['phone']);
            
            if ($existing_user) {
                // User exists, log them in
                $session_data = [
                    'user_id' => $existing_user['id'],
                    'user_name' => $existing_user['name'],
                    'user_email' => $existing_user['email'] ?? null,
                    'logged_in' => true
                ];
                $this->session->set_userdata($session_data);
                $user_id = $existing_user['id'];
            } else {
                // Create new user account
                $user_data = [
                    'name' => $form_data['name'],
                    'email' => !empty($form_data['email']) ? $form_data['email'] : null,
                    'phone' => $form_data['phone'],
                    'whatsapp' => $form_data['phone'] // Use phone as whatsapp
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

        // Generate kundli
        $kundli_data = [
            'user_id' => $user_id,
            'name' => $form_data['name'],
            'birth_date' => $form_data['dob'],
            'birth_time' => $form_data['tob'],
            'birth_place' => $form_data['pob'],
            'phone' => $form_data['phone'],
            'email' => !empty($form_data['email']) ? $form_data['email'] : null,
            'payment_status' => 'COMPLETED',
            'order_id' => $orderId,
            'amount' => isset($form_data['kundli_type']) && $form_data['kundli_type'] === 'detailed' ? 101.00 : 51.00,
            'kundli_data' => json_encode([
                'gender' => $form_data['gender'],
                'language' => $form_data['language'],
                'kundli_type' => $form_data['kundli_type'],
                'lat' => isset($form_data['lat']) ? $form_data['lat'] : null,
                'long' => isset($form_data['long']) ? $form_data['long'] : null
            ])
        ];

        $kundli_id = $this->User_model->save_kundli($kundli_data);
        if (!$kundli_id) {
            log_message('error', 'Failed to save kundli for order: ' . $orderId);
            show_error('Failed to generate kundli');
            return;
        }

        // Update the database record with completion status
        $this->load->model('frontend/Contactmodel');
        $this->Contactmodel->update_kundli_by_order_id($orderId, [
            'payment_status' => 'COMPLETED',
            'kundli_id' => $kundli_id,
            'user_id' => $user_id,
            'processed_at' => date('Y-m-d H:i:s')
        ]);

        // Clear session data
        $this->session->unset_userdata(['kundli_form_data', 'merchant_order_id']);

        // Set success message and store kundli ID for dashboard
        $this->session->set_flashdata('success', 'Payment successful! Your Kundli has been generated successfully.');
        $this->session->set_flashdata('new_kundli_id', $kundli_id);

        log_message('info', 'Payment confirmation completed successfully for order: ' . $orderId . ', kundli_id: ' . $kundli_id);

        // Redirect to dashboard first, then it will show the new kundli
        redirect('dashboard');
    }

    /**
     * 🔹 Step 3: Payment cancel callback
     */
    public function payment_cancel()
    {
        $orderId = $this->input->get('orderId') ?? 'N/A';
        
        // Update database record if exists
        if ($orderId !== 'N/A') {
            $this->load->model('frontend/Contactmodel');
            $this->Contactmodel->update_kundli_by_order_id($orderId, [
                'payment_status' => 'CANCELLED',
                'cancelled_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        // Clear session data
        $this->session->unset_userdata(['kundli_form_data', 'merchant_order_id']);
        
        // Set flash message and redirect to form
        $this->session->set_flashdata('error', 'Payment was cancelled. You can try again whenever you\'re ready.');
        redirect('generate-kundli');
    }

    /**
     * 🔹 Step 4: Payment failure callback
     */
    public function payment_failure()
    {
        $orderId = $this->input->get('orderId') ?? 'N/A';
        
        // Update database record if exists
        if ($orderId !== 'N/A') {
            $this->load->model('frontend/Contactmodel');
            $this->Contactmodel->update_kundli_by_order_id($orderId, [
                'payment_status' => 'FAILED',
                'failed_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        // Clear session data
        $this->session->unset_userdata(['kundli_form_data', 'merchant_order_id']);
        
        // Set flash message and redirect to form
        $this->session->set_flashdata('error', 'Payment failed. Please try again or contact support if money was debited.');
        redirect('generate-kundli');
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

    /**
     * Verify payment status with PhonePe
     */
    private function verify_payment_status($orderId)
    {
        $phonepe_url = site_url('phonepe/order_status?orderId=' . urlencode($orderId));

        $ch = curl_init($phonepe_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $http_code !== 200) {
            log_message('error', 'Payment status verification failed for order: ' . $orderId . ', error: ' . $error . ', http_code: ' . $http_code);
            return 'ERROR';
        }

        $status_data = json_decode($response, true);
        
        // Check for successful payment
        if (isset($status_data['code']) && $status_data['code'] === 'SUCCESS' && 
            isset($status_data['data']['state']) && $status_data['data']['state'] === 'COMPLETED') {
            return 'SUCCESS';
        }

        // Log the actual response for debugging
        log_message('debug', 'Payment status response for order ' . $orderId . ': ' . $response);
        
        return isset($status_data['data']['state']) ? $status_data['data']['state'] : 'UNKNOWN';
    }
}
