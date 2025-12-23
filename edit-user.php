<?php
require_once 'includes/functions.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: manage-users.php");
    exit();
}

$user_id = intval($_GET['id']);
$user = getUserById($conn, $user_id);

if (!$user) {
    $_SESSION['error'] = "User not found";
    header("Location: manage-users.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $role = sanitize($_POST['role']);
    
    if (empty($full_name) || empty($role)) {
        $error = "All fields are required";
    } elseif (!in_array($role, ['user', 'admin'])) {
        $error = "Invalid role";
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssi", $full_name, $role, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "User updated successfully";
            header("Location: manage-users.php");
            exit();
        } else {
            $error = "Failed to update user";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="form_section">
        <div class="container form_section_container">
            <h2>Edit User</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                <small style="display: block; margin-top: -0.5rem; margin-bottom: 0.8rem; color: var(--color-gray-200);">
                    Username cannot be changed
                </small>
                
                <input type="text" name="full_name" placeholder="Full Name" required 
                       value="<?php echo htmlspecialchars($user['full_name']); ?>">
                
                <select name="role" required>
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
                
                <button type="submit" class="btn">Update User</button>
                <a href="manage-users.php" class="btn" style="background: var(--color-gray-700); text-align: center;">Cancel</a>
            </form>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>