<?php
require_once 'includes/functions.php';
requireAdmin();

// Get all users
$users = $conn->query("SELECT id, username, email, full_name, role, created_at 
                       FROM users 
                       ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="dashboard">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success container">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger container">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="container dashboard_container">
            <aside>
                <ul>
                    <li>
                        <a href="admin-dashboard.php">
                            <i class="uil uil-postcard"></i>
                            <h5>Manage Posts</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-users.php" class="active">
                            <i class="uil uil-users-alt"></i>
                            <h5>Manage Users</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-categories.php">
                            <i class="uil uil-list-ul"></i>
                            <h5>Categories</h5>
                        </a>
                    </li>
                    <li>
                        <a href="add-post.php">
                            <i class="uil uil-plus"></i>
                            <h5>Add Post</h5>
                        </a>
                    </li>
                    <li>
                        <a href="dashboard.php">
                            <i class="uil uil-user"></i>
                            <h5>My Posts</h5>
                        </a>
                    </li>
                </ul>
            </aside>

            <main>
                <h2>Manage Users</h2>
                
                <?php if ($users->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span style="background: <?php echo $user['role'] === 'admin' ? 'var(--color-primary)' : 'var(--color-gray-700)'; ?>; 
                                             padding: 0.3rem 0.8rem; border-radius: var(--card-border-radius-2); font-size: 0.85rem;">
                                    <?php echo strtoupper($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <a href="edit-user.php?id=<?php echo $user['id']; ?>" 
                                       class="btn" 
                                       style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                        <i class="uil uil-edit"></i>
                                    </a>
                                    <a href="delete-user.php?id=<?php echo $user['id']; ?>" 
                                       class="btn" 
                                       style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background: var(--color-red);"
                                       onclick="return confirm('Are you sure you want to delete this user? All their posts will also be deleted.')">
                                        <i class="uil uil-trash-alt"></i>
                                    </a>
                                <?php else: ?>
                                    <span style="font-size: 0.85rem; color: var(--color-gray-700);">You</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align: center; padding: 2rem;">No users found.</p>
                <?php endif; ?>
            </main>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>