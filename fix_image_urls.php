<?php
require_once 'config/database_sqlite.php';

echo "=== Fixing Malformed Image URLs ===\n";

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

// Get all items with image URLs
$query = 'SELECT id, name, image_url FROM menu_items WHERE image_url IS NOT NULL AND image_url != ""';
$stmt = $pdo->query($query);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fixed = 0;
$skipped = 0;

foreach($items as $item) {
    $currentUrl = $item['image_url'];
    $newUrl = $currentUrl;
    
    // Check if URL is malformed (contains localhost with path issues)
    if (strpos($currentUrl, 'http://localhost') !== false || strpos($currentUrl, 'https://localhost') !== false) {
        // Extract just the uploads/menu-items/filename part
        if (preg_match('/uploads\/menu-items\/([^\/]+)$/', $currentUrl, $matches)) {
            $newUrl = 'uploads/menu-items/' . $matches[1];
            
            // Update the database
            $updateStmt = $pdo->prepare('UPDATE menu_items SET image_url = ? WHERE id = ?');
            $updateStmt->execute([$newUrl, $item['id']]);
            
            echo "FIXED: ID {$item['id']} | {$item['name']}\n";
            echo "  OLD: {$currentUrl}\n";
            echo "  NEW: {$newUrl}\n\n";
            $fixed++;
        } else {
            echo "COULD NOT FIX: ID {$item['id']} | {$item['name']} | {$currentUrl}\n\n";
        }
    } else {
        echo "OK: ID {$item['id']} | {$item['name']} | {$currentUrl}\n";
        $skipped++;
    }
}

echo "=== Summary ===\n";
echo "Fixed: $fixed items\n";
echo "Skipped (OK): $skipped items\n";
echo "Total processed: " . ($fixed + $skipped) . " items\n";
?>
