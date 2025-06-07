<?php
require_once 'config/database_sqlite.php';

echo "Updating admin password hash...\n";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Generate proper password hash for 'admin123'
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Update the admin user with proper password hash
    $stmt = $conn->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'admin'");
    $result = $stmt->execute([$password_hash]);
    
    if ($result) {
        echo "✓ Admin password hash updated successfully\n";
        
        // Verify the update
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = 'admin'");
        $stmt->execute();
        $admin = $stmt->fetch();
        
        if ($admin && password_verify('admin123', $admin['password_hash'])) {
            echo "✓ Password verification now works correctly\n";
        } else {
            echo "✗ Password verification still failing\n";
        }
    } else {
        echo "✗ Failed to update admin password hash\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\nAdmin credentials:\n";
echo "Username: admin\n";
echo "Password: admin123\n";
?>
