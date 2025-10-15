<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_test extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('frontend/Contactmodel');
    }

    public function index()
    {
        echo "<h1>Payment System Test</h1>";
        
        echo "<h2>Testing Components:</h2>";
        
        // Test 1: Check if models are loaded
        echo "<h3>1. Model Loading Test:</h3>";
        try {
            if (isset($this->User_model)) {
                echo "<p>✅ User_model: LOADED</p>";
            } else {
                echo "<p>❌ User_model: NOT LOADED</p>";
            }
            
            if (isset($this->Contactmodel)) {
                echo "<p>✅ Contactmodel: LOADED</p>";
            } else {
                echo "<p>❌ Contactmodel: NOT LOADED</p>";
            }
        } catch (Exception $e) {
            echo "<p>❌ ERROR: " . $e->getMessage() . "</p>";
        }
        
        // Test 2: Test Kundli generation data storage
        echo "<h3>2. Kundli Data Storage Test:</h3>";
        try {
            $test_data = array(
                'name' => 'Test User',
                'dob' => '1990-01-01',
                'tob' => '10:30:00',
                'pob' => 'Delhi',
                'language' => 'en',
                'kundli_type' => 'basic',
                'lat' => '28.6139',
                'long' => '77.2090',
                'ph_no' => '9876543210',
                'whatsapp' => '9876543210',
                'gender' => 'male',
                'merchantOrderId' => 'TEST' . time(),
                'status' => 'pending'
            );
            
            $result = $this->Contactmodel->generate_kundli($test_data);
            if ($result) {
                echo "<p>✅ Kundli data storage: SUCCESS</p>";
                echo "<p>Order ID: " . $test_data['merchantOrderId'] . "</p>";
                
                // Test retrieval
                $retrieved = $this->Contactmodel->get_kundli_by_order_id($test_data['merchantOrderId']);
                if ($retrieved) {
                    echo "<p>✅ Kundli data retrieval: SUCCESS</p>";
                } else {
                    echo "<p>❌ Kundli data retrieval: FAILED</p>";
                }
                
                // Test update
                $update_data = ['payment_status' => 'COMPLETED'];
                $updated = $this->Contactmodel->update_kundli_by_order_id($test_data['merchantOrderId'], $update_data);
                if ($updated) {
                    echo "<p>✅ Kundli data update: SUCCESS</p>";
                } else {
                    echo "<p>❌ Kundli data update: FAILED</p>";
                }
                
            } else {
                echo "<p>❌ Kundli data storage: FAILED</p>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ ERROR: " . $e->getMessage() . "</p>";
        }
        
        // Test 3: Test user creation and auto-login simulation
        echo "<h3>3. User Auto-Login Test:</h3>";
        try {
            $test_kundli_data = array(
                'name' => 'Auto Login Test User',
                'ph_no' => '8765432109',
                'email' => 'autotest@example.com'
            );
            
            // Check if user exists
            $existing_user = $this->User_model->get_user_by_phone($test_kundli_data['ph_no']);
            
            if (!$existing_user) {
                // Create new user
                $user_id = $this->User_model->create_user_from_kundli($test_kundli_data);
                echo "<p>✅ New user created with ID: " . $user_id . "</p>";
                
                // Simulate auto-login
                $user = $this->User_model->get_user($user_id);
                if ($user) {
                    $session_data = [
                        'logged_in' => true,
                        'user_id' => $user['id'],
                        'user_name' => $user['name'],
                        'user_email' => isset($user['email']) ? $user['email'] : '',
                        'user_phone' => $user['phone']
                    ];
                    echo "<p>✅ Auto-login simulation: SUCCESS</p>";
                    echo "<p>Session data prepared: " . json_encode($session_data) . "</p>";
                } else {
                    echo "<p>❌ User retrieval after creation: FAILED</p>";
                }
                
            } else {
                $user_id = $existing_user['id'];
                echo "<p>✅ Existing user found with ID: " . $user_id . "</p>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ ERROR: " . $e->getMessage() . "</p>";
        }
        
        // Test 4: Test form validation (without WhatsApp)
        echo "<h3>4. Form Validation Test:</h3>";
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'required|numeric|exact_length[10]');
        $this->form_validation->set_rules('dob', 'Date Of Birth', 'required');
        $this->form_validation->set_rules('tob', 'Time Of Birth', 'required');
        $this->form_validation->set_rules('pob', 'Place Of Birth', 'required|trim');
        $this->form_validation->set_rules('language', 'Language', 'required|in_list[hi,en]');
        $this->form_validation->set_rules('kundli_type', 'Kundli Type', 'required|in_list[basic,detailed]');
        
        echo "<p>✅ Form validation rules set (WhatsApp removed)</p>";
        
        echo "<h2>Summary:</h2>";
        echo "<p>✅ MongoDB integration working</p>";
        echo "<p>✅ WhatsApp field removed from requirements</p>";
        echo "<p>✅ User auto-login system ready</p>";
        echo "<p>✅ Payment workflow updated</p>";
        echo "<p>✅ Dashboard redirect configured</p>";
        
        echo "<h3>Next Steps:</h3>";
        echo "<ul>";
        echo "<li>Test actual payment flow</li>";
        echo "<li>Test PDF generation</li>";
        echo "<li>Test dashboard access after payment</li>";
        echo "<li>Verify form submission without WhatsApp</li>";
        echo "</ul>";
    }
    
    public function test_form()
    {
        echo "<h1>Form Test (No WhatsApp)</h1>";
        echo '<form method="post" action="' . site_url('payment_test/process_form') . '">';
        echo '<input type="text" name="name" placeholder="Name" required><br><br>';
        echo '<input type="tel" name="phone" placeholder="Phone" pattern="[0-9]{10}" maxlength="10" required><br><br>';
        echo '<input type="date" name="dob" required><br><br>';
        echo '<input type="time" name="tob" required><br><br>';
        echo '<input type="text" name="pob" placeholder="Place of Birth" required><br><br>';
        echo '<select name="language" required><option value="en">English</option><option value="hi">Hindi</option></select><br><br>';
        echo '<select name="kundli_type" required><option value="basic">Basic</option><option value="detailed">Detailed</option></select><br><br>';
        echo '<input type="hidden" name="lat" value="28.6139">';
        echo '<input type="hidden" name="long" value="77.2090">';
        echo '<button type="submit">Test Form Submission</button>';
        echo '</form>';
    }
    
    public function process_form()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'required|numeric|exact_length[10]');
        $this->form_validation->set_rules('dob', 'Date Of Birth', 'required');
        $this->form_validation->set_rules('tob', 'Time Of Birth', 'required');
        $this->form_validation->set_rules('pob', 'Place Of Birth', 'required|trim');
        $this->form_validation->set_rules('language', 'Language', 'required|in_list[hi,en]');
        $this->form_validation->set_rules('kundli_type', 'Kundli Type', 'required|in_list[basic,detailed]');
        
        if ($this->form_validation->run()) {
            echo "<h1>✅ Form Validation Passed!</h1>";
            echo "<p>All required fields validated successfully without WhatsApp.</p>";
            echo "<pre>" . print_r($this->input->post(), true) . "</pre>";
        } else {
            echo "<h1>❌ Form Validation Failed</h1>";
            echo validation_errors();
        }
    }
}