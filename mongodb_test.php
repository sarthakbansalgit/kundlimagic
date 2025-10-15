<?php
// Simple MongoDB Atlas Data API Test
require_once 'application/config/autoload.php';

// Test configuration
$app_id = 'data-qdjse';
$api_key = 'ZMEr4ZePKovvNFWEu9qAHWCNZ1YcvQHZYhpFNJ0xvA60VNmJLEGKUhBjIDJTyOTi';
$cluster_name = 'Cluster0';
$database = 'kundali_magic';
$base_url = "https://data.mongodb-api.com/app/{$app_id}/endpoint/data/v1";

// Test insert a document
function test_mongodb_insert() {
    global $base_url, $api_key, $cluster_name, $database;
    
    $url = "{$base_url}/action/insertOne";
    
    $test_data = [
        'collection' => 'test_users',
        'database' => $database,
        'dataSource' => $cluster_name,
        'document' => [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
    
    $headers = [
        'Content-Type: application/json',
        'Access-Control-Request-Headers: *',
        'api-key: ' . $api_key
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test_data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    echo "<h3>MongoDB Insert Test</h3>";
    echo "<p><strong>HTTP Code:</strong> {$http_code}</p>";
    
    if ($curl_error) {
        echo "<p><strong>CURL Error:</strong> {$curl_error}</p>";
        return false;
    }
    
    echo "<p><strong>Response:</strong></p>";
    echo "<pre>" . print_r(json_decode($response, true), true) . "</pre>";
    
    return $http_code === 200;
}

// Test find documents
function test_mongodb_find() {
    global $base_url, $api_key, $cluster_name, $database;
    
    $url = "{$base_url}/action/find";
    
    $test_data = [
        'collection' => 'test_users',
        'database' => $database,
        'dataSource' => $cluster_name,
        'filter' => []
    ];
    
    $headers = [
        'Content-Type: application/json',
        'Access-Control-Request-Headers: *',
        'api-key: ' . $api_key
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test_data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    echo "<h3>MongoDB Find Test</h3>";
    echo "<p><strong>HTTP Code:</strong> {$http_code}</p>";
    
    if ($curl_error) {
        echo "<p><strong>CURL Error:</strong> {$curl_error}</p>";
        return false;
    }
    
    echo "<p><strong>Response:</strong></p>";
    echo "<pre>" . print_r(json_decode($response, true), true) . "</pre>";
    
    return $http_code === 200;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>MongoDB Atlas Data API Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow: auto; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>MongoDB Atlas Data API Test</h1>
    
    <?php
    echo "<h2>Configuration</h2>";
    echo "<p><strong>App ID:</strong> {$app_id}</p>";
    echo "<p><strong>Cluster:</strong> {$cluster_name}</p>";
    echo "<p><strong>Database:</strong> {$database}</p>";
    echo "<p><strong>Base URL:</strong> {$base_url}</p>";
    
    // Run tests
    $insert_success = test_mongodb_insert();
    $find_success = test_mongodb_find();
    
    echo "<h2>Test Results</h2>";
    echo "<p>Insert Test: " . ($insert_success ? '<span class="success">PASSED</span>' : '<span class="error">FAILED</span>') . "</p>";
    echo "<p>Find Test: " . ($find_success ? '<span class="success">PASSED</span>' : '<span class="error">FAILED</span>') . "</p>";
    
    if ($insert_success && $find_success) {
        echo "<h3 class='success'>✅ MongoDB Atlas Data API is working correctly!</h3>";
        echo "<p>You can now use the MongoDB integration in your CodeIgniter application.</p>";
    } else {
        echo "<h3 class='error'>❌ MongoDB Atlas Data API test failed</h3>";
        echo "<p>Please check your configuration and API credentials.</p>";
    }
    ?>
</body>
</html>