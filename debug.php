<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PHP Error Debug</h1>";

// Test 1: Check if config files exist
echo "<h2>Config Files Check:</h2>";
$config_files = [
    'application/config/config.php',
    'application/config/database.php', 
    'application/config/mongodb_api.php',
    'application/config/autoload.php'
];

foreach ($config_files as $file) {
    echo "<p>$file: " . (file_exists($file) ? '✅ EXISTS' : '❌ MISSING') . "</p>";
}

// Test 2: Check if libraries exist
echo "<h2>Library Files Check:</h2>";
$lib_files = [
    'application/libraries/Mongodb_api.php',
    'application/models/User_model.php'
];

foreach ($lib_files as $file) {
    echo "<p>$file: " . (file_exists($file) ? '✅ EXISTS' : '❌ MISSING') . "</p>";
}

// Test 3: Try to load CodeIgniter bootstrap
echo "<h2>CodeIgniter Bootstrap Test:</h2>";
try {
    if (file_exists('system/core/CodeIgniter.php')) {
        echo "<p>CodeIgniter core file: ✅ EXISTS</p>";
    } else {
        echo "<p>CodeIgniter core file: ❌ MISSING</p>";
    }
    
    // Try basic config loading
    define('BASEPATH', realpath('system/') . '/');
    define('APPPATH', realpath('application/') . '/');
    
    // Try to include the config
    if (file_exists(APPPATH . 'config/config.php')) {
        include_once(APPPATH . 'config/config.php');
        echo "<p>Config loaded: ✅ SUCCESS</p>";
        
        if (isset($config['base_url'])) {
            echo "<p>Base URL: " . $config['base_url'] . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ ERROR: " . $e->getMessage() . "</p>";
}

echo "<h2>PHP Info:</h2>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Extensions: " . implode(', ', get_loaded_extensions()) . "</p>";
?>