<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS - DISABLED FOR MONGODB
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
| 
| MONGODB IS NOW USED INSTEAD OF MYSQL
| See config/mongodb.php for MongoDB configuration
|
*/

// Disable MySQL database autoload
$active_group = 'default';
$query_builder = false; // Disabled for MongoDB

// Empty MySQL config - not used
$db['default'] = array(
    'dsn'    => '',
    'hostname' => '',
    'username' => '',
    'password' => '',
    'database' => '',
    'dbdriver' => '',
    'dbprefix' => '',
    'pconnect' => false,
    'db_debug' => false,
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => array(),
    'save_queries' => false
);