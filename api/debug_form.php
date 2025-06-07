<?php
// Debug script to check form submission and image URL handling
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Log incoming request
error_log("Debug form submission - Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Debug form submission - Raw input: " . file_get_contents('php://input'));

require_once '../config/database_sqlite.php';
require_once '../classes/MenuManager.php';

$menu_manager = new MenuManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    error_log("Debug form submission - Decoded input: " . json_encode($input));
    
    if (!$input) {
        echo json_encode(['error' => 'No input data received', 'debug' => 'Input is null or empty']);
        exit;
    }
    
    // Check required fields
    $missing_fields = [];
    if (!isset($input['name']) || empty($input['name'])) $missing_fields[] = 'name';
    if (!isset($input['price']) || empty($input['price'])) $missing_fields[] = 'price';
    if (!isset($input['category']) || empty($input['category'])) $missing_fields[] = 'category';
    
    if (!empty($missing_fields)) {
        echo json_encode([
            'error' => 'Missing required fields: ' . implode(', ', $missing_fields),
            'received_data' => $input,
            'debug' => 'Required field validation failed'
        ]);
        exit;
    }
    
    // Debug image_url specifically
    $image_url = isset($input['image_url']) ? $input['image_url'] : '';
    error_log("Debug form submission - Image URL: '$image_url'");
    
    echo json_encode([
        'success' => true,
        'message' => 'Debug - would save item successfully',
        'received_data' => $input,
        'image_url_received' => $image_url,
        'image_url_empty' => empty($image_url),
        'debug' => 'All validation passed'
    ]);
} else {
    echo json_encode(['error' => 'Only POST method supported in debug mode']);
}
?>
