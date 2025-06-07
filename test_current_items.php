<?php
require_once 'classes/MenuManager.php';
require_once 'config/database_sqlite.php';

try {
    $menu = new MenuManager();
    $items = $menu->getAllItems();
    
    echo "Total menu items: " . count($items) . "\n";
    echo "Recent items:\n";
    
    foreach(array_slice($items, 0, 5) as $item) {
        echo "- " . $item['name'] . " (Image: " . $item['image_url'] . ")\n";
    }
    
    echo "\nDatabase connection: OK\n";
    echo "Image upload directory exists: " . (is_dir('uploads/menu-items') ? 'YES' : 'NO') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
