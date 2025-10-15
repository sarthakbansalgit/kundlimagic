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
            echo json_encode([
                'status'  => 'error',
                'message' => 'Payment service temporarily unavailable. Please try again later.'
            ]);
            return;
        }

        if ($http_code !== 200) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Payment service error. Please try again later.'
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

        // Check if user is logged in
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            // Create user account
            $user_data = [
                'name' => $form_data['name'],
                'email' => $form_data['email'] ?? null,
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

        // Generate kundli
        $kundli_data = [
            'user_id' => $user_id,
            'name' => $form_data['name'],
            'birth_date' => $form_data['dob'],
            'birth_time' => $form_data['tob'],
            'birth_place' => $form_data['pob'],
            'kundli_data' => json_encode([
                'gender' => $form_data['gender'],
                'language' => $form_data['language'],
                'kundli_type' => $form_data['kundli_type'],
                'lat' => $form_data['lat'],
                'long' => $form_data['long']
            ])
        ];

        $kundli_id = $this->User_model->save_kundli($kundli_data);
        if (!$kundli_id) {
            show_error('Failed to generate kundli');
            return;
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
