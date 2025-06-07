<!DOCTYPE html>
<html>
<head>
    <title>Image Path Test</title>
</head>
<body>
    <h1>Image Path Test</h1>
    
    <h2>From Root Directory (menu.html perspective)</h2>
    <p>Testing: uploads/menu-items/menu_item_6844a517e74ca_1749329175.jpeg</p>
    <img src="uploads/menu-items/menu_item_6844a517e74ca_1749329175.jpeg" alt="Test from root" style="max-width: 200px; border: 1px solid red;">
    
    <h2>From Admin Directory (admin/index.php perspective)</h2>
    <p>Testing: ../uploads/menu-items/menu_item_6844a517e74ca_1749329175.jpeg</p>
    <img src="../uploads/menu-items/menu_item_6844a517e74ca_1749329175.jpeg" alt="Test from admin" style="max-width: 200px; border: 1px solid blue;">
    
    <h2>All uploaded files:</h2>
    <?php
    $uploadDir = 'uploads/menu-items/';
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        foreach($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "<div><strong>$file</strong><br>";
                echo "<img src='uploads/menu-items/$file' alt='$file' style='max-width: 150px; border: 1px solid green; margin: 5px;'></div>";
            }
        }
    }
    ?>
</body>
</html>
