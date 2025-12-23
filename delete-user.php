<?php
require_once 'includes/functions.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: manage-users.php");
    exit();
}

$user_id = intval($_GET['id']);

// Prevent admin from deleting themselves
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error'] = "You cannot delete your own account";
    header("Location: manage-users.php");
    exit();
}

// Check if user exists
$user = getUserById($conn, $user_id);
if (!$user) {
    $_SESSION['error'] = "User not found";
    header("Location: manage-users.php");
    exit();
}

// Delete user (posts will be deleted via CASCADE)
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete user";
}

header("Location: manage-users.php");
exit();
?>