<?php
require_once 'config/database.php';

echo "<h1>Image Debug Tool</h1>";

// Get the latest post
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 1");
$post = $result->fetch_assoc();

echo "<h2>Latest Post Details:</h2>";
echo "<p><strong>Title:</strong> " . $post['title'] . "</p>";
echo "<p><strong>Image filename in DB:</strong> " . $post['image'] . "</p>";
echo "<p><strong>Author ID:</strong> " . $post['author_id'] . "</p>";

echo "<h2>File System Check:</h2>";
echo "<p><strong>Current directory:</strong> " . getcwd() . "</p>";

$uploadPath = 'uploads/' . $post['image'];
$imagePath = 'Image/' . $post['image'];

echo "<p><strong>Checking uploads path:</strong> " . $uploadPath . "</p>";
echo "<p>Exists: " . (file_exists($uploadPath) ? '<span style="color:green">YES ✓</span>' : '<span style="color:red">NO ✗</span>') . "</p>";

echo "<p><strong>Checking Image path:</strong> " . $imagePath . "</p>";
echo "<p>Exists: " . (file_exists($imagePath) ? '<span style="color:green">YES ✓</span>' : '<span style="color:red">NO ✗</span>') . "</p>";

echo "<h2>Actual files in uploads/:</h2>";
$files = glob('uploads/*');
if (empty($files)) {
    echo "<p>No files found</p>";
} else {
    foreach ($files as $file) {
        echo "<p>- " . $file . "</p>";
    }
}

echo "<h2>Test Display:</h2>";
if (file_exists($uploadPath)) {
    echo "<p>Image from uploads/:</p>";
    echo "<img src='" . $uploadPath . "' width='300'><br>";
} elseif (file_exists($imagePath)) {
    echo "<p>Image from Image/:</p>";
    echo "<img src='" . $imagePath . "' width='300'><br>";
} else {
    echo "<p style='color:red'>Image not found in either location!</p>";
}

// List all uploads folder contents
echo "<h2>Full uploads directory listing:</h2>";
$uploadDir = __DIR__ . '/uploads/';
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<p>- $file (size: " . filesize($uploadDir . $file) . " bytes)</p>";
        }
    }
} else {
    echo "<p style='color:red'>uploads/ directory doesn't exist!</p>";
}
?>
