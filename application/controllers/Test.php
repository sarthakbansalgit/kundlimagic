<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        echo "<h1>✅ CodeIgniter is Working!</h1>";
        echo "<p>MongoDB API Library: ";
        
        try {
            $this->load->library('mongodb_api');
            echo "✅ LOADED</p>";
            
            echo "<h2>MongoDB Configuration Test:</h2>";
            if (isset($this->mongodb_api)) {
                echo "<p>MongoDB API Object: ✅ AVAILABLE</p>";
                
                // Test a simple MongoDB operation
                echo "<h3>Testing MongoDB Connection:</h3>";
                $test_data = [
                    'test' => true,
                    'timestamp' => date('Y-m-d H:i:s'),
                    'message' => 'Hello from CodeIgniter!'
                ];
                
                $result = $this->mongodb_api->insert('test_collection', $test_data);
                if ($result) {
                    echo "<p>✅ MongoDB Insert Test: SUCCESS</p>";
                } else {
                    echo "<p>❌ MongoDB Insert Test: FAILED</p>";
                }
                
            } else {
                echo "<p>❌ MongoDB API Object: NOT AVAILABLE</p>";
            }
            
        } catch (Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "</p>";
        }
        
        echo "<h2>User Model Test:</h2>";
        try {
            $this->load->model('User_model');
            echo "<p>User Model: ✅ LOADED</p>";
            
            // Test if we can call model methods
            if (method_exists($this->User_model, 'email_exists')) {
                echo "<p>email_exists method: ✅ AVAILABLE</p>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ User Model ERROR: " . $e->getMessage() . "</p>";
        }
        
        echo "<h2>Session & Libraries:</h2>";
        echo "<p>Session: " . (isset($this->session) ? "✅ LOADED" : "❌ NOT LOADED") . "</p>";
        echo "<p>Form Validation: " . (isset($this->form_validation) ? "✅ LOADED" : "❌ NOT LOADED") . "</p>";
        
        echo "<h2>Config Information:</h2>";
        echo "<p>Base URL: " . $this->config->item('base_url') . "</p>";
        echo "<p>Index Page: " . $this->config->item('index_page') . "</p>";
    }
    
    public function registration()
    {
        echo "<h1>Registration Test</h1>";
        
        try {
            $this->load->model('User_model');
            
            $test_user_data = [
                'name' => 'Test User',
                'email' => 'test' . time() . '@example.com',
                'password' => 'test123',
                'phone' => '1234567890'
            ];
            
            $user_id = $this->User_model->create_user($test_user_data);
            
            if ($user_id) {
                echo "<p>✅ User created successfully with ID: " . $user_id . "</p>";
                
                // Test login
                $login_result = $this->User_model->login($test_user_data['email'], 'test123');
                if ($login_result) {
                    echo "<p>✅ Login test successful</p>";
                } else {
                    echo "<p>❌ Login test failed</p>";
                }
                
            } else {
                echo "<p>❌ User creation failed</p>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ ERROR: " . $e->getMessage() . "</p>";
        }
    }
}