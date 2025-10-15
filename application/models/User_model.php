<?php
defined('BASEPATH') || exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $collection = 'users';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('mongodb_simple');
    }

    public function create_user($data)
    {
        // Hash password if not already hashed
        if (isset($data['password']) && !password_get_info($data['password'])['algo']) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Add ID and timestamps
        $data['_id'] = $this->mongodb_simple->generate_id();
        $data['created_at'] = $this->mongodb_simple->get_timestamp();
        $data['updated_at'] = $this->mongodb_simple->get_timestamp();
        
        $result = $this->mongodb_simple->insert($this->collection, $data);
        return $result ? $data['_id'] : false;
    }

    public function login($email, $password)
    {
        $user = $this->mongodb_simple->find_one($this->collection, ['email' => $email]);
        
        if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
            // Set ID for session
            $user['id'] = $user['_id'];
            return $user;
        }
        return false;
    }

    public function get_user($user_id)
    {
        $user = $this->mongodb_simple->find_one($this->collection, ['_id' => $user_id]);
        
        if ($user) {
            $user['id'] = $user['_id'];
            return (object)$user; // Convert array to object for view compatibility
        }
        
        return null;
    }

    public function update_user($user_id, $data)
    {
        $data['updated_at'] = $this->mongodb_simple->get_timestamp();
        return $this->mongodb_simple->update_one($this->collection, ['_id' => $user_id], $data);
    }

    public function get_user_kundlis($user_id)
    {
        $kundlis = $this->mongodb_simple->find('kundlis', 
            ['user_id' => $user_id], 
            ['sort' => ['created_at' => -1]]
        );
        
        // Convert to array format for compatibility
        return array_map(function($kundli) {
            $kundli['id'] = $kundli['_id'];
            return (object)$kundli;
        }, $kundlis);
    }

    public function save_kundli($data)
    {
        $data['_id'] = $this->mongodb_simple->generate_id();
        $data['created_at'] = $this->mongodb_simple->get_timestamp();
        $result = $this->mongodb_simple->insert('kundlis', $data);
        return $result ? $data['_id'] : false;
    }

    public function get_kundli($kundli_id, $user_id = null)
    {
        $filter = ['_id' => $kundli_id];
        if ($user_id) {
            $filter['user_id'] = $user_id;
        }
        
        $kundli = $this->mongodb_simple->find_one('kundlis', $filter);
        
        if ($kundli) {
            $kundli['id'] = $kundli['_id'];
            return (object)$kundli;
        }
        
        return null;
    }

    // Get user by phone/whatsapp number
    public function get_user_by_phone($phone)
    {
        // Try to find by whatsapp first
        $user = $this->mongodb_simple->find_one($this->collection, ['whatsapp' => $phone]);
        if ($user) {
            $user['id'] = $user['_id'];
            return $user;
        }

        // Fallback: look for generic phone column
        $user = $this->mongodb_simple->find_one($this->collection, ['phone' => $phone]);
        if ($user) {
            $user['id'] = $user['_id'];
            return $user;
        }
        
        return null;
    }

    // Create a minimal user record from kundli data and return user ID
    public function create_user_from_kundli($kundliData)
    {
        $now = $this->mongodb_simple->get_timestamp();
        $password = password_hash(bin2hex(random_bytes(8)), PASSWORD_BCRYPT);

        // Determine whatsapp/phone values cleanly
        $whatsapp = null;
        if (isset($kundliData['whatsapp']) && !empty($kundliData['whatsapp'])) {
            $whatsapp = $kundliData['whatsapp'];
        } elseif (isset($kundliData['ph_no'])) {
            $whatsapp = $kundliData['ph_no'];
        }

        $phone = isset($kundliData['ph_no']) ? $kundliData['ph_no'] : null;

        $data = array(
            '_id'        => $this->mongodb_simple->generate_id(),
            'name'       => isset($kundliData['name']) ? $kundliData['name'] : 'User',
            'email'      => isset($kundliData['email']) ? $kundliData['email'] : null,
            'whatsapp'   => $whatsapp,
            'phone'      => $phone,
            'password'   => $password,
            'created_at' => $now,
            'updated_at' => $now,
        );

        // Remove nulls to avoid issues
        $data = array_filter($data, function($v) { return $v !== null; });

        $result = $this->mongodb_simple->insert($this->collection, $data);
        return $result ? $data['_id'] : false;
    }

    // Check if email exists
    public function email_exists($email)
    {
        $user = $this->mongodb_simple->find_one($this->collection, ['email' => $email]);
        return !empty($user);
    }

    // Get user by email
    public function get_user_by_email($email)
    {
        $user = $this->mongodb_simple->find_one($this->collection, ['email' => $email]);
        if ($user) {
            $user['id'] = $user['_id'];
            return (object)$user; // Convert to object for consistency
        }
        return null;
    }
}