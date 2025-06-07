<?php
// Test admin authentication API
echo "Testing Admin API directly...\n\n";

// Simulate a POST request to admin_auth.php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [];
$GLOBALS['HTTP_RAW_POST_DATA'] = json_encode([
    'action' => 'login',
    'username' => 'admin',
    'password' => 'admin123'
]);

// Capture the output
ob_start();

// Mock the php://input
$json_input = json_encode([
    'action' => 'login',
    'username' => 'admin',
    'password' => 'admin123'
]);

// Temporarily override file_get_contents for php://input
function file_get_contents_override($filename) {
    if ($filename === 'php://input') {
        return $GLOBALS['HTTP_RAW_POST_DATA'];
    }
    return file_get_contents($filename);
}

// Include the API file
include 'api/admin_auth.php';

$output = ob_get_contents();
ob_end_clean();

echo "API Response:\n";
echo $output;
?>
