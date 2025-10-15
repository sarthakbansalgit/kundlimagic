<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Api extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('User_model');
        
        // Set JSON header
        header('Content-Type: application/json');
    }

    public function index()
    {
        $this->output->set_output(json_encode([
            'status' => 'success',
            'message' => 'Kundali Magic API is working',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0'
        ]));
    }

    public function test()
    {
        $this->output->set_output(json_encode([
            'status' => 'success',
            'message' => 'API test endpoint working',
            'database' => $this->test_database(),
            'timestamp' => date('Y-m-d H:i:s')
        ]));
    }

    private function test_database()
    {
        try {
            $this->load->database();
            $query = $this->db->query("SELECT 1 as test");
            if ($query) {
                return 'connected';
            }
            return 'error';
        } catch (Exception $e) {
            return 'failed: ' . $e->getMessage();
        }
    }

    public function places($query = '')
    {
        // Simple place search API (you can integrate with Google Places API here)
        $query = $this->input->get('q') ?: $query;
        
        if (empty($query)) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Query parameter required'
            ]));
            return;
        }

        // Mock response - replace with actual place search API
        $places = [
            ['name' => 'Delhi, India', 'lat' => 28.6139, 'lng' => 77.2090],
            ['name' => 'Mumbai, India', 'lat' => 19.0760, 'lng' => 72.8777],
            ['name' => 'Bangalore, India', 'lat' => 12.9716, 'lng' => 77.5946],
            ['name' => 'Chennai, India', 'lat' => 13.0827, 'lng' => 80.2707],
            ['name' => 'Kolkata, India', 'lat' => 22.5726, 'lng' => 88.3639]
        ];

        $filtered = array_filter($places, function($place) use ($query) {
            return stripos($place['name'], $query) !== false;
        });

        $this->output->set_output(json_encode([
            'status' => 'success',
            'query' => $query,
            'results' => array_values($filtered)
        ]));
    }

    public function user_stats()
    {
        try {
            $this->load->database();
            
            // Get user count
            $user_count = $this->db->count_all('users');
            
            // Get kundli count
            $kundli_count = $this->db->count_all('kundlis');
            
            $this->output->set_output(json_encode([
                'status' => 'success',
                'data' => [
                    'total_users' => $user_count,
                    'total_kundlis' => $kundli_count,
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ]));
            
        } catch (Exception $e) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]));
        }
    }
}