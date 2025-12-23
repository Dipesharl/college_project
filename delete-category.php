<?php
require_once 'includes/functions.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: manage-categories.php");
    exit();
}

$category_id = intval($_GET['id']);

// Check if category has posts
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM posts WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    $_SESSION['error'] = "Cannot delete category with existing posts. Please reassign or delete posts first.";
    header("Location: manage-categories.php");
    exit();
}

// Delete category
$stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Category deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete category";
}

header("Location: manage-categories.php");
exit();
?>