<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple MongoDB-like Data Storage for testing
 * Uses JSON files to simulate MongoDB collections
 */
class Mongodb_simple {
    
    private $CI;
    private $data_dir;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->data_dir = APPPATH . 'data/';
        
        // Create data directory if it doesn't exist
        if (!is_dir($this->data_dir)) {
            mkdir($this->data_dir, 0755, true);
        }
        
        log_message('info', 'MongoDB Simple Library Initialized');
    }
    
    /**
     * Insert a document into a collection
     */
    public function insert($collection, $data) {
        $file_path = $this->data_dir . $collection . '.json';
        
        // Load existing data
        $existing_data = [];
        if (file_exists($file_path)) {
            $json_data = file_get_contents($file_path);
            $existing_data = json_decode($json_data, true) ?: [];
        }
        
        // Add new document
        $existing_data[] = $data;
        
        // Save back to file with error handling
        $result = @file_put_contents($file_path, json_encode($existing_data, JSON_PRETTY_PRINT), LOCK_EX);
        
        if ($result === false) {
            log_message('error', 'Failed to write to collection: ' . $collection);
            return false;
        }
        
        return true;
    }
    
    /**
     * Find documents in a collection
     */
    public function find($collection, $filter = [], $options = []) {
        $file_path = $this->data_dir . $collection . '.json';
        
        if (!file_exists($file_path)) {
            return [];
        }
        
        $json_data = file_get_contents($file_path);
        $data = json_decode($json_data, true) ?: [];
        
        // Apply filter
        if (!empty($filter)) {
            $data = array_filter($data, function($item) use ($filter) {
                foreach ($filter as $key => $value) {
                    if (!isset($item[$key]) || $item[$key] !== $value) {
                        return false;
                    }
                }
                return true;
            });
            $data = array_values($data);
        }
        
        // Apply sorting if specified
        if (isset($options['sort']) && is_array($options['sort'])) {
            foreach ($options['sort'] as $sort_key => $sort_direction) {
                usort($data, function($a, $b) use ($sort_key, $sort_direction) {
                    $val_a = isset($a[$sort_key]) ? $a[$sort_key] : '';
                    $val_b = isset($b[$sort_key]) ? $b[$sort_key] : '';
                    
                    if ($sort_direction === -1 || $sort_direction === 'desc') {
                        return $val_b <=> $val_a;
                    }
                    return $val_a <=> $val_b;
                });
            }
        }
        
        // Apply limit if specified
        if (isset($options['limit']) && is_numeric($options['limit'])) {
            $data = array_slice($data, 0, (int)$options['limit']);
        }
        
        return $data;
    }
    
    /**
     * Find one document in a collection
     */
    public function find_one($collection, $filter = []) {
        $results = $this->find($collection, $filter);
        return !empty($results) ? $results[0] : null;
    }
    
    /**
     * Update documents in a collection
     */
    public function update($collection, $filter, $update_data, $options = []) {
        $file_path = $this->data_dir . $collection . '.json';
        
        if (!file_exists($file_path)) {
            return false;
        }
        
        $json_data = file_get_contents($file_path);
        $data = json_decode($json_data, true) ?: [];
        
        $updated = 0;
        foreach ($data as &$item) {
            $match = true;
            foreach ($filter as $key => $value) {
                if (!isset($item[$key]) || $item[$key] !== $value) {
                    $match = false;
                    break;
                }
            }
            
            if ($match) {
                foreach ($update_data as $key => $value) {
                    $item[$key] = $value;
                }
                $updated++;
            }
        }
        
        if ($updated > 0) {
            file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));
        }
        
        return $updated;
    }
    
    /**
     * Update one document in a collection
     */
    public function update_one($collection, $filter, $update_data, $options = []) {
        return $this->update($collection, $filter, $update_data, $options);
    }
    
    /**
     * Delete documents from a collection
     */
    public function delete($collection, $filter) {
        $file_path = $this->data_dir . $collection . '.json';
        
        if (!file_exists($file_path)) {
            return 0;
        }
        
        $json_data = file_get_contents($file_path);
        $data = json_decode($json_data, true) ?: [];
        
        $original_count = count($data);
        
        $data = array_filter($data, function($item) use ($filter) {
            foreach ($filter as $key => $value) {
                if (isset($item[$key]) && $item[$key] === $value) {
                    return false; // Delete this item
                }
            }
            return true; // Keep this item
        });
        
        $deleted = $original_count - count($data);
        
        if ($deleted > 0) {
            file_put_contents($file_path, json_encode(array_values($data), JSON_PRETTY_PRINT));
        }
        
        return $deleted;
    }
    
    /**
     * Delete one document from a collection
     */
    public function delete_one($collection, $filter) {
        return $this->delete($collection, $filter);
    }
    
    /**
     * Count documents in a collection
     */
    public function count($collection, $filter = []) {
        return count($this->find($collection, $filter));
    }
    
    /**
     * Generate a unique ID
     */
    public function generate_id() {
        return uniqid() . bin2hex(random_bytes(8));
    }
    
    /**
     * Get current timestamp
     */
    public function get_timestamp() {
        return date('Y-m-d H:i:s');
    }
}