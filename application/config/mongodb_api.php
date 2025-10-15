<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| MongoDB Atlas Data API Configuration
|--------------------------------------------------------------------------
| 
| Configuration for MongoDB Atlas Data API integration
| This approach doesn't require the MongoDB PHP extension
|
*/

$config['mongodb'] = array(
    // MongoDB Atlas Data API Configuration
    'app_id' => 'data-qdjse', // MongoDB Atlas App ID
    'api_key' => 'ZMEr4ZePKovvNFWEu9qAHWCNZ1YcvQHZYhpFNJ0xvA60VNmJLEGKUhBjIDJTyOTi', // Data API Key
    'cluster_name' => 'Cluster0',
    'database' => 'kundali_magic',
    
    // Atlas connection details (for reference)
    'connection_string' => 'mongodb+srv://kundlimagiceniacworldcom_db_user:CIZnYfDZqeVx0Ijw@cluster0.ysmg3vp.mongodb.net/',
    
    // Collection mappings
    'collections' => array(
        'users' => 'users',
        'kundlis' => 'kundlis',
        'payments' => 'payments',
        'sessions' => 'sessions',
        'logs' => 'logs'
    )
);