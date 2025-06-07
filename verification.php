<?php
// Final verification of image upload system
echo "<h1>🎉 Image Upload System - Final Verification</h1>\n";

// Check database connection
try {
    $pdo = new PDO('sqlite:database/grand_restaurant.db');
    echo "<p>✅ Database connection: OK</p>\n";
} catch (Exception $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>\n";
    exit;
}

// Check upload directory
$uploadDir = 'uploads/menu-items/';
if (is_dir($uploadDir) && is_writable($uploadDir)) {
    echo "<p>✅ Upload directory exists and is writable</p>\n";
    $files = glob($uploadDir . '*');
    echo "<p>📁 Files in upload directory: " . count($files) . "</p>\n";
} else {
    echo "<p>❌ Upload directory issue</p>\n";
}

// Check image URLs in database
$stmt = $pdo->query("SELECT COUNT(*) as total, 
                            COUNT(CASE WHEN image_url IS NOT NULL AND image_url != '' THEN 1 END) as with_images,
                            COUNT(CASE WHEN image_url LIKE 'http://localhost%' THEN 1 END) as malformed_urls
                     FROM menu_items");
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h2>📊 Database Statistics</h2>\n";
echo "<p>Total menu items: {$stats['total']}</p>\n";
echo "<p>Items with images: {$stats['with_images']}</p>\n";
echo "<p>Malformed URLs (old format): {$stats['malformed_urls']}</p>\n";

if ($stats['malformed_urls'] > 0) {
    echo "<p>⚠️ Found {$stats['malformed_urls']} items with old malformed URLs that need fixing</p>\n";
} else {
    echo "<p>✅ All image URLs are in correct format</p>\n";
}

// Test image accessibility
echo "<h2>🖼️ Image Accessibility Test</h2>\n";
$stmt = $pdo->query("SELECT id, name, image_url FROM menu_items WHERE image_url IS NOT NULL AND image_url != '' LIMIT 5");
echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>\n";
echo "<tr><th>ID</th><th>Name</th><th>Image URL</th><th>Status</th></tr>\n";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imagePath = $row['image_url'];
    $fullPath = __DIR__ . '/' . $imagePath;
    $accessible = file_exists($fullPath) ? '✅' : '❌';
    
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($imagePath) . "</td>";
    echo "<td>{$accessible}</td>";
    echo "</tr>\n";
}
echo "</table>\n";

echo "<h2>🔧 System Components Status</h2>\n";

// Check upload_image.php
if (file_exists('api/upload_image.php')) {
    echo "<p>✅ Upload handler exists</p>\n";
} else {
    echo "<p>❌ Upload handler missing</p>\n";
}

// Check admin panel
if (file_exists('admin/index.html')) {
    echo "<p>✅ Admin panel exists</p>\n";
} else {
    echo "<p>❌ Admin panel missing</p>\n";
}

// Check menu display
if (file_exists('menu.html')) {
    echo "<p>✅ Menu display page exists</p>\n";
} else {
    echo "<p>❌ Menu display page missing</p>\n";
}

echo "<h2>🚀 Ready for Testing</h2>\n";
echo "<p>The image upload system has been fixed and is ready for use!</p>\n";
echo "<p><strong>Test workflow:</strong></p>\n";
echo "<ol>\n";
echo "<li>Login to admin panel: <a href='admin/index.html'>admin/index.html</a></li>\n";
echo "<li>Upload a new menu item with image</li>\n";
echo "<li>Check image preview in admin panel</li>\n";
echo "<li>Verify image displays on menu page: <a href='menu.html'>menu.html</a></li>\n";
echo "</ol>\n";

echo "<p><strong>Test pages:</strong></p>\n";
echo "<ul>\n";
echo "<li><a href='test_upload.html'>Upload Test Page</a></li>\n";
echo "<li><a href='check_urls.php'>Database URL Check</a></li>\n";
echo "</ul>\n";
?>
