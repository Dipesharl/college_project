<?php
require_once 'includes/functions.php';
requireLogin();

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = intval($_GET['id']);
$post = getPostById($conn, $post_id);

if (!$post) {
    $_SESSION['error'] = "Post not found";
    header("Location: dashboard.php");
    exit();
}

// Check if user owns this post or is admin
if ($post['author_id'] != $_SESSION['user_id'] && !isAdmin()) {
    $_SESSION['error'] = "You don't have permission to delete this post";
    header("Location: dashboard.php");
    exit();
}

// Delete the post
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    // Delete image if exists
    if ($post['image']) {
        deleteImage($post['image']);
    }
    $_SESSION['success'] = "Post deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete post";
}

// Redirect to appropriate dashboard
if (isAdmin() && isset($_GET['from']) && $_GET['from'] === 'admin') {
    header("Location: admin-dashboard.php");
} else {
    header("Location: dashboard.php");
}
exit();
?>