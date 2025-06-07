<?php
// Check current image URLs in database
$pdo = new PDO('sqlite:database/grand_restaurant.db');
$stmt = $pdo->query('SELECT id, name, image_url FROM menu_items WHERE image_url IS NOT NULL AND image_url != "" ORDER BY id LIMIT 10');

echo "<h2>Current Image URLs in Database</h2>\n";
echo "<table border='1' style='border-collapse: collapse;'>\n";
echo "<tr><th>ID</th><th>Name</th><th>Image URL</th><th>Preview</th></tr>\n";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imageUrl = htmlspecialchars($row['image_url']);
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>{$imageUrl}</td>";
    echo "<td><img src='{$imageUrl}' style='width: 50px; height: 50px; object-fit: cover;' onerror='this.style.display=\"none\";this.nextSibling.style.display=\"inline\"'><span style='display:none; color:red;'>❌</span></td>";
    echo "</tr>\n";
}

echo "</table>\n";
?>
