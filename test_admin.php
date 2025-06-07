<?php
// Test script to check admin authentication
require_once 'config/database_sqlite.php';

echo "Testing Admin Authentication...\n\n";

try {
    // Test database connection
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "✓ Database connection successful\n";
        
        // Check if admin_users table exists
        $stmt = $conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name='admin_users'");
        $table_exists = $stmt->fetch();
        
        if ($table_exists) {
            echo "✓ admin_users table exists\n";
            
            // Check for admin user
            $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
            $stmt->execute(['admin']);
            $admin = $stmt->fetch();
            
            if ($admin) {
                echo "✓ Admin user found in database\n";
                echo "  Username: " . $admin['username'] . "\n";
                echo "  Email: " . $admin['email'] . "\n";
                
                // Test password verification
                $test_password = 'admin123';
                if (password_verify($test_password, $admin['password_hash'])) {
                    echo "✓ Password verification works\n";
                } else {
                    echo "✗ Password verification failed\n";
                    echo "  Trying hardcoded login...\n";
                    if ($admin['username'] === 'admin' && $test_password === 'admin123') {
                        echo "✓ Hardcoded login would work\n";
                    }
                }
            } else {
                echo "✗ No admin user found, creating one...\n";
                $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)");
                $result = $stmt->execute(['admin', $password_hash, 'admin@grandrestaurant.com']);
                
                if ($result) {
                    echo "✓ Admin user created successfully\n";
                } else {
                    echo "✗ Failed to create admin user\n";
                }
            }
        } else {
            echo "✗ admin_users table does not exist\n";
        }
        
    } else {
        echo "✗ Database connection failed\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\nTesting complete. You can delete this file after testing.\n";
?>
