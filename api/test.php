<?php
header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'message' => 'API test endpoint working',
    'timestamp' => date('Y-m-d H:i:s'),
    'server' => $_SERVER['SERVER_NAME'] ?? 'localhost'
]);
?>
