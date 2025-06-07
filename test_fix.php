<?php
// Test the fixed menu item creation
require_once '../config/database_sqlite.php';
require_once '../classes/MenuManager.php';

echo "<h1>Testing Fixed Menu Item Creation</h1>\n";

$menu_manager = new MenuManager();

// Test data
$test_data = [
    'name' => 'Test Fix Item',
    'description' => 'Testing the fix for image URL saving',
    'price' => 19.99,
    'category' => 'Rice & Curry',
    'image_url' => 'uploads/menu-items/test_image.jpg',
    'is_available' => 1
];

echo "<h2>Test Data</h2>\n";
echo "<pre>" . json_encode($test_data, JSON_PRETTY_PRINT) . "</pre>\n";

try {
    // Test adding the item
    $result = $menu_manager->addItem(
        $test_data['name'],
        $test_data['description'],
        $test_data['price'],
        $test_data['category'],
        $test_data['image_url'],
        $test_data['is_available']
    );
    
    if ($result) {
        echo "<h2>✅ Success!</h2>\n";
        echo "<p>Menu item created successfully with image URL!</p>\n";
        
        // Get the latest item to verify
        $pdo = new PDO('sqlite:../database/grand_restaurant.db');
        $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY id DESC LIMIT 1");
        $latest_item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h2>Latest Item in Database</h2>\n";
        echo "<pre>" . json_encode($latest_item, JSON_PRETTY_PRINT) . "</pre>\n";
        
        // Check if image URL was saved correctly
        if (!empty($latest_item['image_url'])) {
            echo "<p>✅ Image URL saved correctly: <strong>{$latest_item['image_url']}</strong></p>\n";
        } else {
            echo "<p>❌ Image URL is empty or not saved</p>\n";
        }
        
    } else {
        echo "<h2>❌ Failed!</h2>\n";
        echo "<p>Failed to create menu item</p>\n";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Error!</h2>\n";
    echo "<p>Error: " . $e->getMessage() . "</p>\n";
}

echo "<p><a href='../admin/index.html'>← Back to Admin Panel</a></p>\n";
?>
