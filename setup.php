<?php
/**
 * Grand Restaurant Setup Script
 * Run this file to initialize the database on a new computer
 * Compatible with MAMP (Mac), XAMPP (Windows), and LAMP (Linux)
 */

// Detect operating system
$os = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : (PHP_OS === 'Darwin' ? 'macOS' : 'Linux');
$is_windows = ($os === 'Windows');

echo "🍽️ Grand Restaurant Setup\n";
echo "========================\n";
echo "Operating System: $os\n";
echo "PHP Version: " . PHP_VERSION . "\n\n";

// Check SQLite support
if (!extension_loaded('sqlite3')) {
    echo "❌ Error: SQLite3 extension is not loaded!\n";
    if ($is_windows) {
        echo "💡 Solution for Windows XAMPP:\n";
        echo "   1. Edit C:\\xampp\\php\\php.ini\n";
        echo "   2. Uncomment: extension=sqlite3\n";
        echo "   3. Restart Apache\n\n";
    } else {
        echo "💡 Solution: Install php-sqlite3 extension\n\n";
    }
    exit(1);
}

// Database configuration
$db_path = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'grand_restaurant.db';
$schema_path = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'schema.sql';

// Check if database already exists
if (file_exists($db_path)) {
    echo "Database already exists at: " . $db_path . "\n";
    echo "If you want to recreate it, delete the existing file first.\n";
    exit;
}

// Create database directory if it doesn't exist
$db_dir = dirname($db_path);
if (!is_dir($db_dir)) {
    mkdir($db_dir, 0755, true);
    echo "Created database directory: " . $db_dir . "\n";
}

try {
    // Create SQLite database connection
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Created new SQLite database: " . $db_path . "\n";
    
    // Read and execute schema
    if (!file_exists($schema_path)) {
        throw new Exception("Schema file not found: " . $schema_path);
    }
    
    $schema = file_get_contents($schema_path);
    if ($schema === false) {
        throw new Exception("Could not read schema file");
    }
    
    // Execute schema (split by semicolon for multiple statements)
    $statements = explode(';', $schema);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "Database schema executed successfully!\n";
    
    // Create uploads directory structure
    $upload_dirs = [
        __DIR__ . '/uploads',
        __DIR__ . '/uploads/menu-items'
    ];
    
    foreach ($upload_dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "Created directory: " . $dir . "\n";
        }
    }
    
    // Set proper permissions (Unix-like systems only)
    if (!$is_windows) {
        chmod($db_path, 0666);
        chmod($db_dir, 0755);
    }
    
    echo "\n✅ Setup completed successfully!\n";
    echo "🔹 Database created and initialized\n";
    echo "🔹 Upload directories created\n";
    echo "🔹 Default admin user created (username: admin, password: admin123)\n";
    echo "🔹 Sample data inserted\n\n";
    
    // Platform-specific instructions
    if ($is_windows) {
        echo "🖥️ Windows XAMPP Instructions:\n";
        echo "   📍 Main Website: http://localhost/restaurant/\n";
        echo "   ⚙️ Admin Panel: http://localhost/restaurant/admin/\n";
        echo "   🔧 API Test: http://localhost/restaurant/api/menu.php\n\n";
        echo "💡 Ensure Apache is running in XAMPP Control Panel\n";
    } else {
        echo "🍎 Mac MAMP Instructions:\n";
        echo "   📍 Main Website: http://localhost:8888/restaurant/\n";
        echo "   ⚙️ Admin Panel: http://localhost:8888/restaurant/admin/\n";
        echo "   🔧 API Test: http://localhost:8888/restaurant/api/menu.php\n\n";
        echo "💡 Ensure MAMP servers are running\n";
    }
    
    echo "Your Grand Restaurant system is ready to use!\n";
    
} catch (Exception $e) {
    echo "❌ Setup failed: " . $e->getMessage() . "\n";
    
    // Platform-specific troubleshooting
    if ($is_windows) {
        echo "\n🛠️ Windows Troubleshooting:\n";
        echo "   1. Check XAMPP Control Panel - Apache should be running\n";
        echo "   2. Verify SQLite3 is enabled in php.ini\n";
        echo "   3. Ensure write permissions to database folder\n";
        echo "   4. Check Windows Defender/Antivirus settings\n";
    } else {
        echo "\n🛠️ Troubleshooting:\n";
        echo "   1. Check file permissions: chmod 755 database/\n";
        echo "   2. Verify SQLite3 extension: php -m | grep sqlite3\n";
        echo "   3. Check web server is running\n";
    }
    
    // Clean up on failure
    if (file_exists($db_path)) {
        unlink($db_path);
    }
}
?>
