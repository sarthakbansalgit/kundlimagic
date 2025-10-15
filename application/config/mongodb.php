<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * MongoDB Configuration for CodeIgniter
 * Updated with your MongoDB Atlas credentials
 */

$config['mongodb'] = array(
    'host' => 'cluster0.ysmg3vp.mongodb.net',
    'port' => 27017,
    'database' => 'kundali_magic',
    'username' => 'kundlimagiceniacworldcom_db_user',
    'password' => 'CIZnYfDZqeVx0Ijw',
    'options' => array(
        'connectTimeoutMS' => 30000,
        'socketTimeoutMS' => 30000,
        'retryWrites' => true,
        'w' => 'majority'
    ),
    // MongoDB Atlas (Cloud) - YOUR CREDENTIALS
    'atlas' => array(
        'enabled' => true,
        'connection_string' => 'mongodb+srv://kundlimagiceniacworldcom_db_user:CIZnYfDZqeVx0Ijw@cluster0.ysmg3vp.mongodb.net/kundali_magic?retryWrites=true&w=majority'
    )
);

// Collection names mapping
$config['collections'] = array(
    'users' => 'users',
    'kundlis' => 'kundlis',
    'orders' => 'orders', // Replaces genrate_kundli
    'contacts' => 'contacts',
    'subscribers' => 'subscribers',
    'sessions' => 'sessions'
);

// MongoDB specific settings
$config['mongodb_settings'] = array(
    'auto_create_indexes' => true,
    'use_transactions' => true,
    'default_write_concern' => 'majority',
    'default_read_preference' => 'primary'
);