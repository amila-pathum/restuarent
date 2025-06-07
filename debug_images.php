<?php
require_once 'config/database_sqlite.php';

echo "=== DEBUG: Image URLs in Database ===\n";

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

$query = 'SELECT id, name, image_url FROM menu_items WHERE image_url IS NOT NULL AND image_url != ""';
$stmt = $pdo->query($query);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total items with images: " . count($items) . "\n\n";

foreach($items as $item) {
    echo "ID: {$item['id']} | Name: {$item['name']} | Image URL: {$item['image_url']}\n";
}

echo "\n=== Files in uploads/menu-items/ ===\n";
$uploadDir = 'uploads/menu-items/';
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    foreach($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "File: $file\n";
        }
    }
} else {
    echo "Upload directory does not exist\n";
}
?>
