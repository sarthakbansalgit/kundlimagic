<!DOCTYPE html>
<html>
<head>
    <title>Configuration Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }
        .section { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #2196F3; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        pre { background: #263238; color: #aed581; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .label { font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Kundali Magic - Configuration Diagnostic</h1>
        
        <?php
        define('BASEPATH', TRUE);
        
        echo '<div class="section">';
        echo '<h2>📁 File Paths</h2>';
        echo '<p><span class="label">Current Directory:</span> ' . getcwd() . '</p>';
        echo '<p><span class="label">Script Path:</span> ' . __FILE__ . '</p>';
        echo '</div>';
        
        // Check database config
        echo '<div class="section">';
        echo '<h2>💾 Database Configuration</h2>';
        
        $db_config_file = __DIR__ . '/application/config/database.php';
        if (file_exists($db_config_file)) {
            require_once($db_config_file);
            
            echo '<p class="success">✅ database.php found</p>';
            echo '<pre>';
            echo "Hostname: " . ($db['default']['hostname'] ?: '(empty)') . "\n";
            echo "Username: " . ($db['default']['username'] ?: '(empty)') . "\n";
            echo "Database: " . ($db['default']['database'] ?: '(empty)') . "\n";
            echo "Driver: " . ($db['default']['dbdriver'] ?: '(empty)') . "\n";
            echo "DB Debug: " . ($db['default']['db_debug'] ? 'TRUE' : 'FALSE') . "\n";
            echo '</pre>';
            
            if (empty($db['default']['hostname']) && empty($db['default']['database'])) {
                echo '<p class="success">✅ MySQL is DISABLED (empty config)</p>';
            } else {
                echo '<p class="error">❌ MySQL config is NOT empty!</p>';
            }
        } else {
            echo '<p class="error">❌ database.php NOT found</p>';
        }
        echo '</div>';
        
        // Check MongoDB config
        echo '<div class="section">';
        echo '<h2>🍃 MongoDB Configuration</h2>';
        
        $mongo_config_file = __DIR__ . '/application/config/mongodb.php';
        if (file_exists($mongo_config_file)) {
            require_once($mongo_config_file);
            echo '<p class="success">✅ mongodb.php found</p>';
            echo '<pre>';
            echo "Connection String: " . substr($config['mongodb']['connection_string'], 0, 50) . "...\n";
            echo "Database: " . $config['mongodb']['database'] . "\n";
            echo '</pre>';
        } else {
            echo '<p class="error">❌ mongodb.php NOT found</p>';
        }
        echo '</div>';
        
        // Check JSON file storage
        echo '<div class="section">';
        echo '<h2>📄 JSON File Storage</h2>';
        
        $users_json = __DIR__ . '/application/data/users.json';
        if (file_exists($users_json)) {
            $users = json_decode(file_get_contents($users_json), true);
            echo '<p class="success">✅ users.json found</p>';
            echo '<p><span class="label">Total Users:</span> ' . count($users) . '</p>';
            if (count($users) > 0) {
                echo '<p><span class="label">Sample Email:</span> ' . $users[0]['email'] . '</p>';
            }
        } else {
            echo '<p class="error">❌ users.json NOT found</p>';
        }
        echo '</div>';
        
        // Check autoload config
        echo '<div class="section">';
        echo '<h2>⚙️ Autoload Configuration</h2>';
        
        $autoload_file = __DIR__ . '/application/config/autoload.php';
        if (file_exists($autoload_file)) {
            require_once($autoload_file);
            echo '<p class="success">✅ autoload.php found</p>';
            echo '<pre>';
            echo "Libraries: " . implode(', ', $autoload['libraries']) . "\n";
            echo '</pre>';
            
            if (in_array('database', $autoload['libraries'])) {
                echo '<p class="error">❌ Database is AUTO-LOADED! This will cause MySQL connection attempts.</p>';
            } else {
                echo '<p class="success">✅ Database is NOT auto-loaded</p>';
            }
            
            if (in_array('mongodb_simple', $autoload['libraries'])) {
                echo '<p class="success">✅ mongodb_simple is auto-loaded</p>';
            } else {
                echo '<p class="warning">⚠️  mongodb_simple is NOT auto-loaded</p>';
            }
        } else {
            echo '<p class="error">❌ autoload.php NOT found</p>';
        }
        echo '</div>';
        
        // PHP Info
        echo '<div class="section">';
        echo '<h2>🐘 PHP Environment</h2>';
        echo '<p><span class="label">PHP Version:</span> ' . PHP_VERSION . '</p>';
        echo '<p><span class="label">OPcache Enabled:</span> ' . (function_exists('opcache_get_status') && opcache_get_status() ? 'YES' : 'NO') . '</p>';
        echo '</div>';
        
        echo '<div class="section">';
        echo '<h2>✅ Next Steps</h2>';
        echo '<ol>';
        echo '<li>Verify all configurations above show MongoDB enabled and MySQL disabled</li>';
        echo '<li>If "Database is AUTO-LOADED" shows red, that\'s the problem</li>';
        echo '<li>Try logging in at <a href="/index.php/auth/login">localhost:8000/index.php/auth/login</a></li>';
        echo '<li>Test registration at <a href="/index.php/auth/register">localhost:8000/index.php/auth/register</a></li>';
        echo '</ol>';
        echo '</div>';
        ?>
    </div>
</body>
</html>
