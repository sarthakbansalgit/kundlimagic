<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MongoDB Library for CodeIgniter - Data API Version
 * 
 * Simple MongoDB integration using Atlas Data API
 * Compatible with PHP 7.4+ without requiring mongodb extension
 */
class Mongodb {
    
    private $CI;
    private $config;
    private $base_url;
    private $api_key;
    private $cluster_name;
    private $database_name;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('mongodb_api');
        $this->config = $this->CI->config->item('mongodb');
        
        // Setup MongoDB Atlas Data API configuration
        $this->cluster_name = $this->config['cluster_name'];
        $this->database_name = $this->config['database'];
        $this->api_key = $this->config['api_key'];
        $this->base_url = "https://data.mongodb-api.com/app/{$this->config['app_id']}/endpoint/data/v1";
        
        log_message('info', 'MongoDB Data API Library Initialized');
    }
    
    /**
     * Insert a document into a collection
     */
    public function insert($collection, $data) {
        $url = "{$this->base_url}/action/insertOne";
        
        $payload = [
            'collection' => $collection,
            'database' => $this->database_name,
            'dataSource' => $this->cluster_name,
            'document' => $data
        ];
        
        $response = $this->make_request('POST', $url, $payload);
        return $response;
    }
    
    /**
     * Find documents in a collection
     */
    public function find($collection, $filter = [], $options = []) {
        $url = "{$this->base_url}/action/find";
        
        $payload = [
            'collection' => $collection,
            'database' => $this->database_name,
            'dataSource' => $this->cluster_name,
            'filter' => $filter
        ];
        
        if (!empty($options)) {
            $payload = array_merge($payload, $options);
        }
        
        $response = $this->make_request('POST', $url, $payload);
        return isset($response['documents']) ? $response['documents'] : [];
    }
    
    /**
     * Find one document in a collection
     */
    public function find_one($collection, $filter = []) {
        $url = "{$this->base_url}/action/findOne";
        
        $payload = [
            'collection' => $collection,
            'database' => $this->database_name,
            'dataSource' => $this->cluster_name,
            'filter' => $filter
        ];
        
        $response = $this->make_request('POST', $url, $payload);
        return isset($response['document']) ? $response['document'] : null;
    }
    
    /**
     * Update documents in a collection
     */
    public function update($collection, $filter, $update_data, $options = []) {
        $url = "{$this->base_url}/action/updateMany";
        
        $payload = [
            'collection' => $collection,
            'database' => $this->database_name,
            'dataSource' => $this->cluster_name,
            'filter' => $filter,
            'update' => ['$set' => $update_data]
        ];
        
        if (!empty($options)) {
            $payload = array_merge($payload, $options);
        }
        
        $response = $this->make_request('POST', $url, $payload);
        return $response;
    }
    
    /**
     * Update one document in a collection
     */
    public function update_one($collection, $filter, $update_data, $options = []) {
        $url = "{$this->base_url}/action/updateOne";
        
        $payload = [
            'collection' => $collection,
            'database' => $this->database_name,
            'dataSource' => $this->cluster_name,
            'filter' => $filter,
            'update' => ['$set' => $update_data]
        ];
        
        if (!empty($options)) {
            $payload = array_merge($payload, $options);
        }
        
        $response = $this->make_request('POST', $url, $payload);
        return $response;
    }
    
    /**
     * Delete documents from a collection
     */
    public function delete($collection, $filter) {
        $url = "{$this->base_url}/action/deleteMany";
        
        $payload = [
            'collection' => $collection,
            'database' => $this->database_name,
            'dataSource' => $this->cluster_name,
            'filter' => $filter
        ];
        
        $response = $this->make_request('POST', $url, $payload);
        return $response;
    }
    
    /**
     * Delete one document from a collection
     */
    public function delete_one($collection, $filter) {
        $url = "{$this->base_url}/action/deleteOne";
        
        $payload = [
            'collection' => $collection,
            'database' => $this->database_name,
            'dataSource' => $this->cluster_name,
            'filter' => $filter
        ];
        
        $response = $this->make_request('POST', $url, $payload);
        return $response;
    }
    
    /**
     * Count documents in a collection
     */
    public function count($collection, $filter = []) {
        $url = "{$this->base_url}/action/aggregate";
        
        $pipeline = [
            ['$match' => $filter],
            ['$count' => 'total']
        ];
        
        $payload = [
            'collection' => $collection,
            'database' => $this->database_name,
            'dataSource' => $this->cluster_name,
            'pipeline' => $pipeline
        ];
        
        $response = $this->make_request('POST', $url, $payload);
        return isset($response['documents'][0]['total']) ? $response['documents'][0]['total'] : 0;
    }
    
    /**
     * Make HTTP request to MongoDB Atlas Data API
     */
    private function make_request($method, $url, $data = []) {
        $headers = [
            'Content-Type: application/json',
            'Access-Control-Request-Headers: *',
            'api-key: ' . $this->api_key
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        if ($curl_error) {
            log_message('error', 'MongoDB CURL Error: ' . $curl_error);
            return false;
        }
        
        if ($http_code !== 200) {
            log_message('error', 'MongoDB HTTP Error: ' . $http_code . ' Response: ' . $response);
            return false;
        }
        
        $decoded_response = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'MongoDB JSON Decode Error: ' . json_last_error_msg());
            return false;
        }
        
        return $decoded_response;
    }
    
    /**
     * Generate MongoDB ObjectId-like string
     */
    public function generate_id() {
        return uniqid() . bin2hex(random_bytes(8));
    }
    
    /**
     * Get current timestamp for MongoDB
     */
    public function get_timestamp() {
        return date('Y-m-d H:i:s');
    }
}