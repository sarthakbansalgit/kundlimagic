<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * MongoDB Library for CodeIgniter
 * Provides MongoDB connectivity and operations
 */

class Mongodb 
{
    private $ci;
    private $client;
    private $database;
    private $config;
    
    public function __construct($config = array())
    {
        $this->ci =& get_instance();
        $this->ci->load->config('mongodb');
        
        $this->config = $this->ci->config->item('mongodb');
        $this->initialize();
        
        log_message('info', 'MongoDB Library Initialized');
    }
    
    private function initialize()
    {
        try {
            // Use MongoDB Atlas connection string
            if ($this->config['atlas']['enabled']) {
                $connectionString = $this->config['atlas']['connection_string'];
            } else {
                $connectionString = sprintf(
                    'mongodb://%s:%s@%s:%d/%s',
                    $this->config['username'],
                    $this->config['password'],
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['database']
                );
            }
            
            // Check if MongoDB extension is available
            if (!extension_loaded('mongodb')) {
                throw new Exception('MongoDB PHP extension is not installed');
            }
            
            // Create MongoDB client
            require_once APPPATH . 'third_party/mongodb/vendor/autoload.php';
            
            $this->client = new MongoDB\Client($connectionString, $this->config['options']);
            $this->database = $this->client->selectDatabase($this->config['database']);
            
            // Test connection
            $this->database->command(['ping' => 1]);
            
        } catch (Exception $e) {
            log_message('error', 'MongoDB Connection Error: ' . $e->getMessage());
            throw new Exception('Failed to connect to MongoDB: ' . $e->getMessage());
        }
    }
    
    public function collection($name)
    {
        $collections = $this->ci->config->item('collections');
        $collectionName = isset($collections[$name]) ? $collections[$name] : $name;
        return $this->database->selectCollection($collectionName);
    }
    
    public function insert($collection, $data)
    {
        try {
            $data['created_at'] = new MongoDB\BSON\UTCDateTime();
            $data['updated_at'] = new MongoDB\BSON\UTCDateTime();
            
            $result = $this->collection($collection)->insertOne($data);
            return $result->getInsertedId();
        } catch (Exception $e) {
            log_message('error', 'MongoDB Insert Error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function find($collection, $filter = [], $options = [])
    {
        try {
            $cursor = $this->collection($collection)->find($filter, $options);
            return iterator_to_array($cursor);
        } catch (Exception $e) {
            log_message('error', 'MongoDB Find Error: ' . $e->getMessage());
            return [];
        }
    }
    
    public function findOne($collection, $filter = [], $options = [])
    {
        try {
            return $this->collection($collection)->findOne($filter, $options);
        } catch (Exception $e) {
            log_message('error', 'MongoDB FindOne Error: ' . $e->getMessage());
            return null;
        }
    }
    
    public function update($collection, $filter, $update, $options = [])
    {
        try {
            $update['$set']['updated_at'] = new MongoDB\BSON\UTCDateTime();
            $result = $this->collection($collection)->updateOne($filter, $update, $options);
            return $result->getModifiedCount();
        } catch (Exception $e) {
            log_message('error', 'MongoDB Update Error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function updateMany($collection, $filter, $update, $options = [])
    {
        try {
            $update['$set']['updated_at'] = new MongoDB\BSON\UTCDateTime();
            $result = $this->collection($collection)->updateMany($filter, $update, $options);
            return $result->getModifiedCount();
        } catch (Exception $e) {
            log_message('error', 'MongoDB UpdateMany Error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function delete($collection, $filter)
    {
        try {
            $result = $this->collection($collection)->deleteOne($filter);
            return $result->getDeletedCount();
        } catch (Exception $e) {
            log_message('error', 'MongoDB Delete Error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function deleteMany($collection, $filter)
    {
        try {
            $result = $this->collection($collection)->deleteMany($filter);
            return $result->getDeletedCount();
        } catch (Exception $e) {
            log_message('error', 'MongoDB DeleteMany Error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function count($collection, $filter = [])
    {
        try {
            return $this->collection($collection)->countDocuments($filter);
        } catch (Exception $e) {
            log_message('error', 'MongoDB Count Error: ' . $e->getMessage());
            return 0;
        }
    }
    
    public function aggregate($collection, $pipeline)
    {
        try {
            $cursor = $this->collection($collection)->aggregate($pipeline);
            return iterator_to_array($cursor);
        } catch (Exception $e) {
            log_message('error', 'MongoDB Aggregate Error: ' . $e->getMessage());
            return [];
        }
    }
    
    public function createIndex($collection, $keys, $options = [])
    {
        try {
            return $this->collection($collection)->createIndex($keys, $options);
        } catch (Exception $e) {
            log_message('error', 'MongoDB CreateIndex Error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getDatabase()
    {
        return $this->database;
    }
    
    public function getClient()
    {
        return $this->client;
    }
}