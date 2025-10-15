<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Model for MongoDB
 */
class User_model extends CI_Model {
    
    private $collection = 'users';
    
    public function __construct() {
        parent::__construct();
        // Load MongoDB API library instead of regular one
        $this->load->library('mongodb_api');
    }
    
    /**
     * Create a new user
     */
    public function create_user($data) {
        $user_data = array(
            '_id' => $this->mongodb_api->generate_id(),
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'full_name' => isset($data['full_name']) ? $data['full_name'] : '',
            'phone' => isset($data['phone']) ? $data['phone'] : '',
            'birth_date' => isset($data['birth_date']) ? $data['birth_date'] : '',
            'birth_time' => isset($data['birth_time']) ? $data['birth_time'] : '',
            'birth_place' => isset($data['birth_place']) ? $data['birth_place'] : '',
            'created_at' => $this->mongodb_api->get_timestamp(),
            'updated_at' => $this->mongodb_api->get_timestamp(),
            'status' => 'active'
        );
        
        $result = $this->mongodb_api->insert($this->collection, $user_data);
        return $result ? $user_data['_id'] : false;
    }
    
    /**
     * Get user by email
     */
    public function get_by_email($email) {
        return $this->mongodb_api->find_one($this->collection, ['email' => $email]);
    }
    
    /**
     * Get user by username
     */
    public function get_by_username($username) {
        return $this->mongodb_api->find_one($this->collection, ['username' => $username]);
    }
    
    /**
     * Get user by ID
     */
    public function get_by_id($user_id) {
        return $this->mongodb_api->find_one($this->collection, ['_id' => $user_id]);
    }
    
    /**
     * Verify user login
     */
    public function verify_login($email, $password) {
        $user = $this->get_by_email($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Update user profile
     */
    public function update_profile($user_id, $data) {
        $update_data = $data;
        $update_data['updated_at'] = $this->mongodb_api->get_timestamp();
        
        $result = $this->mongodb_api->update_one(
            $this->collection, 
            ['_id' => $user_id], 
            $update_data
        );
        
        return $result !== false;
    }
    
    /**
     * Update password
     */
    public function update_password($user_id, $new_password) {
        $update_data = [
            'password' => password_hash($new_password, PASSWORD_DEFAULT),
            'updated_at' => $this->mongodb_api->get_timestamp()
        ];
        
        $result = $this->mongodb_api->update_one(
            $this->collection, 
            ['_id' => $user_id], 
            $update_data
        );
        
        return $result !== false;
    }
    
    /**
     * Delete user
     */
    public function delete_user($user_id) {
        return $this->mongodb_api->delete_one($this->collection, ['_id' => $user_id]);
    }
    
    /**
     * Get all users (admin function)
     */
    public function get_all_users($limit = 50, $offset = 0) {
        return $this->mongodb_api->find(
            $this->collection, 
            [], 
            ['limit' => $limit, 'skip' => $offset, 'sort' => ['created_at' => -1]]
        );
    }
    
    /**
     * Count total users
     */
    public function count_users() {
        return $this->mongodb_api->count($this->collection);
    }
    
    /**
     * Check if email exists
     */
    public function email_exists($email) {
        $user = $this->get_by_email($email);
        return !empty($user);
    }
    
    /**
     * Check if username exists
     */
    public function username_exists($username) {
        $user = $this->get_by_username($username);
        return !empty($user);
    }
}