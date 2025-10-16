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

        // Log received data for debugging
        log_message('debug', 'Payment initiation - Received data: ' . json_encode($data));
        
        // Validate required fields
        $required_fields = ['name', 'phone', 'gender', 'dob', 'tob', 'pob', 'language', 'kundli_type'];
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            log_message('error', 'Payment initiation - Missing fields: ' . implode(', ', $missing_fields));
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Please fill all required fields: ' . implode(', ', $missing_fields)
            ]);
            return;
        }
        
        // Validate lat/long coordinates
        if (empty($data['lat']) || empty($data['long'])) {
            log_message('error', 'Payment initiation - Missing coordinates');
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Please select a valid place of birth from the dropdown'
            ]);
            return;
        }

        // Sanitize and validate input data
        $data['name'] = trim(strip_tags($data['name']));
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        $data['pob'] = trim(strip_tags($data['pob']));
        
        // Validate phone number (10 digits)
        if (strlen($data['phone']) !== 10) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Phone number must be 10 digits'
            ]);
            return;
        }
        
        // Validate email if provided
        if (isset($data['email']) && !empty($data['email'])) {
            $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid email address'
                ]);
                return;
            }
        }
        
        // Validate gender
        if (!in_array($data['gender'], ['male', 'female'])) {
            $data['gender'] = 'male';
        }
        
        // Validate kundli_type
        if (!in_array($data['kundli_type'], ['basic', 'detailed'])) {
            $data['kundli_type'] = 'basic';
        }
        
        // Validate language
        if (!in_array($data['language'], ['hi', 'en'])) {
            $data['language'] = 'en';
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

        // Log PhonePe response
        log_message('debug', 'PhonePe Response - HTTP Code: ' . $http_code . ', Response: ' . $response);

        header('Content-Type: application/json');

        if ($error) {
            log_message('error', 'PhonePe CURL Error: ' . $error);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Payment service temporarily unavailable. Please try again later.',
                'debug' => ENVIRONMENT === 'development' ? $error : null
            ]);
            return;
        }

        if ($http_code !== 200) {
            log_message('error', 'PhonePe HTTP Error: ' . $http_code . ' - ' . $response);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Payment service error. Please try again later.',
                'debug' => ENVIRONMENT === 'development' ? ['http_code' => $http_code, 'response' => $response] : null
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

        // Verify payment status with PhonePe
        $phonepe_url = site_url('phonepe/order_status?orderId=' . urlencode($orderId));
        $ch = curl_init($phonepe_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200 || !$response) {
            $this->session->set_flashdata('error', 'Unable to verify payment status. Please contact support with your Order ID: ' . $orderId);
            redirect('generate-kundli');
            return;
        }

        $payment_status = json_decode($response, true);
        
        // Check if payment was successful
        // PhonePe returns status in different formats, check for common success indicators
        $is_payment_success = false;
        if (isset($payment_status['code']) && $payment_status['code'] === 'SUCCESS') {
            $is_payment_success = true;
        } elseif (isset($payment_status['status']) && strtoupper($payment_status['status']) === 'SUCCESS') {
            $is_payment_success = true;
        } elseif (isset($payment_status['data']['orderStatus']) && strtoupper($payment_status['data']['orderStatus']) === 'COMPLETED') {
            $is_payment_success = true;
        }

        if (!$is_payment_success) {
            $this->session->set_flashdata('error', 'Payment was not completed successfully. Please try again or contact support.');
            redirect('generate-kundli');
            return;
        }

        // Check if user is logged in
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            // Check if user exists by phone number
            $existing_user = null;
            if (!empty($form_data['phone'])) {
                $existing_user = $this->User_model->get_user_by_phone($form_data['phone']);
            }

            if ($existing_user) {
                // User exists, log them in
                $session_data = [
                    'user_id' => $existing_user['id'],
                    'user_name' => $existing_user['name'],
                    'user_email' => isset($existing_user['email']) ? $existing_user['email'] : null,
                    'logged_in' => true
                ];
                $this->session->set_userdata($session_data);
                $user_id = $existing_user['id'];
            } else {
                // Create new user account
                $user_data = [
                    'name' => $form_data['name'],
                    'email' => isset($form_data['email']) && !empty($form_data['email']) ? $form_data['email'] : null,
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
                        'user_email' => isset($user->email) ? $user->email : null,
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

        // Generate kundli with PDF
        $pdf_filename = 'kundli_' . $user_id . '_' . time() . '.pdf';
        $pdf_path = 'uploads/kundlis/' . $pdf_filename;
        
        // Generate PDF content
        $pdf_content = $this->generate_kundli_pdf($form_data, $user_id);
        
        // Save PDF to file
        $full_path = FCPATH . $pdf_path;
        if (!file_exists(dirname($full_path))) {
            mkdir(dirname($full_path), 0755, true);
        }
        file_put_contents($full_path, $pdf_content);
        
        // Save kundli data to database
        $kundli_data = [
            'user_id' => $user_id,
            'name' => $form_data['name'],
            'birth_date' => $form_data['dob'],
            'birth_time' => $form_data['tob'],
            'birth_place' => $form_data['pob'],
            'local_pdf_path' => $pdf_path,
            'order_id' => $orderId,
            'kundli_data' => json_encode([
                'gender' => $form_data['gender'],
                'language' => $form_data['language'],
                'kundli_type' => $form_data['kundli_type'],
                'lat' => $form_data['lat'],
                'long' => $form_data['long'],
                'pdf_filename' => $pdf_filename
            ])
        ];

        $kundli_id = $this->User_model->save_kundli($kundli_data);
        if (!$kundli_id) {
            show_error('Failed to generate kundli');
            return;
        }
        
        log_message('info', 'Kundli generated successfully - ID: ' . $kundli_id . ', User: ' . $user_id);

        // Clear session data
        $this->session->unset_userdata(['kundli_form_data', 'merchant_order_id']);

        // Redirect to dashboard to view kundli
        redirect('dashboard/kundli/' . $kundli_id);
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
    
    /**
     * Generate Kundli PDF content
     */
    private function generate_kundli_pdf($form_data, $user_id)
    {
        // Load PDF library
        $this->load->library('pdf');
        
        // Create PDF content using TCPDF or DOMPDF
        // For now, generate a simple HTML-based PDF
        $html = $this->generate_kundli_html($form_data);
        
        // Try to use DOMPDF if available
        if (file_exists(APPPATH . 'libraries/dompdf/autoload.inc.php')) {
            require_once APPPATH . 'libraries/dompdf/autoload.inc.php';
            
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            return $dompdf->output();
        }
        
        // Fallback: Return HTML as text (will be saved as PDF-like file)
        return $html;
    }
    
    /**
     * Generate Kundli HTML content
     */
    private function generate_kundli_html($form_data)
    {
        $kundli_type_label = ($form_data['kundli_type'] === 'detailed') ? 'Detailed Kundli' : 'Basic Kundli';
        $language_label = ($form_data['language'] === 'hi') ? 'Hindi' : 'English';
        
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kundli Report - ' . htmlspecialchars($form_data['name']) . '</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #ff7010; padding-bottom: 20px; }
        .header h1 { color: #ff7010; margin: 0; font-size: 32px; }
        .header h2 { color: #333; margin: 10px 0 0 0; font-size: 24px; }
        .section { margin: 25px 0; padding: 20px; background: #f9f9f9; border-left: 4px solid #ff7010; }
        .section h3 { color: #ff7010; margin-top: 0; font-size: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .info-row { display: table; width: 100%; margin: 10px 0; }
        .info-label { display: table-cell; width: 40%; font-weight: bold; color: #555; padding: 8px 0; }
        .info-value { display: table-cell; width: 60%; color: #333; padding: 8px 0; }
        .footer { margin-top: 50px; text-align: center; color: #888; font-size: 12px; border-top: 2px solid #ddd; padding-top: 20px; }
        .chart-placeholder { background: #fff; border: 2px solid #ff7010; padding: 40px; text-align: center; margin: 20px 0; min-height: 200px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔯 KUNDLI MAGIC</h1>
        <h2>Personalized Kundli Report</h2>
        <p style="color: #666; margin-top: 10px;">Generated on ' . date('F d, Y \a\t h:i A') . '</p>
    </div>
    
    <div class="section">
        <h3>📋 Personal Information</h3>
        <div class="info-row">
            <div class="info-label">Name:</div>
            <div class="info-value">' . htmlspecialchars($form_data['name']) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Gender:</div>
            <div class="info-value">' . ucfirst($form_data['gender']) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">' . htmlspecialchars($form_data['phone']) . '</div>
        </div>
    </div>
    
    <div class="section">
        <h3>🌟 Birth Details</h3>
        <div class="info-row">
            <div class="info-label">Date of Birth:</div>
            <div class="info-value">' . date('F d, Y', strtotime($form_data['dob'])) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Time of Birth:</div>
            <div class="info-value">' . $form_data['tob'] . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Place of Birth:</div>
            <div class="info-value">' . htmlspecialchars($form_data['pob']) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Coordinates:</div>
            <div class="info-value">Latitude: ' . $form_data['lat'] . ', Longitude: ' . $form_data['long'] . '</div>
        </div>
    </div>
    
    <div class="section">
        <h3>📊 Kundli Details</h3>
        <div class="info-row">
            <div class="info-label">Kundli Type:</div>
            <div class="info-value">' . $kundli_type_label . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Language:</div>
            <div class="info-value">' . $language_label . '</div>
        </div>
    </div>
    
    <div class="section">
        <h3>🔮 Birth Chart</h3>
        <div class="chart-placeholder">
            <p style="color: #666; font-size: 16px;">Your personalized birth chart has been generated based on your birth details.</p>
            <p style="color: #888; font-size: 14px; margin-top: 20px;">This chart represents the position of planets at the time of your birth.</p>
        </div>
    </div>
    
    <div class="section">
        <h3>✨ Astrological Summary</h3>
        <p style="color: #555; line-height: 1.8;">
            Your kundli has been prepared according to Vedic astrology principles. 
            This report includes your birth details, planetary positions, and astrological insights.
            The positions of celestial bodies at your birth time influence various aspects of your life including 
            personality, career, relationships, health, and fortune.
        </p>
    </div>
    
    <div class="footer">
        <p><strong>Kundli Magic - Vedic Astrology Services</strong></p>
        <p>For support: help@kundlimagic.com</p>
        <p style="margin-top: 15px;">© ' . date('Y') . ' Kundli Magic. All rights reserved.</p>
        <p style="color: #aaa; font-size: 11px; margin-top: 10px;">This is an automatically generated report. Results are based on Vedic astrology calculations.</p>
    </div>
</body>
</html>';
        
        return $html;
    }
}
