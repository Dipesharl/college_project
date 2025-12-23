<?php
require_once 'includes/functions.php';
requireAdmin();

// Get statistics
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalPosts = $conn->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'];
$totalCategories = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];

// Get all posts
$allPosts = $conn->query("SELECT p.*, u.full_name as author_name, c.name as category_name 
                          FROM posts p 
                          JOIN users u ON p.author_id = u.id 
                          JOIN categories c ON p.category_id = c.id 
                          ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        .stat-card {
            background: var(--color-primary);
            padding: 1.5rem;
            border-radius: var(--card-border-radius-2);
            text-align: center;
        }
        .stat-card h3 {
            font-size: 2.5rem;
            margin: 0.5rem 0;
        }
        .stat-card p {
            color: var(--color-white);
            opacity: 0.9;
        }
    </style>
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
                        <a href="admin-dashboard.php" class="active">
                            <i class="uil uil-postcard"></i>
                            <h5>Manage Posts</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-users.php">
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
                <h2>Admin Dashboard</h2>
                
                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="uil uil-postcard" style="font-size: 2rem;"></i>
                        <h3><?php echo $totalPosts; ?></h3>
                        <p>Total Posts</p>
                    </div>
                    <div class="stat-card">
                        <i class="uil uil-users-alt" style="font-size: 2rem;"></i>
                        <h3><?php echo $totalUsers; ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="stat-card">
                        <i class="uil uil-list-ul" style="font-size: 2rem;"></i>
                        <h3><?php echo $totalCategories; ?></h3>
                        <p>Categories</p>
                    </div>
                </div>

                <h3>All Posts</h3>
                
                <?php if ($allPosts->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($post = $allPosts->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <a href="post.php?id=<?php echo $post['id']; ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                                <?php if ($post['is_featured']): ?>
                                    <span style="background: var(--color-green); padding: 0.2rem 0.5rem; border-radius: 3px; font-size: 0.7rem; margin-left: 0.5rem;">FEATURED</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                            <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                            <td>
                                <a href="edit-post.php?id=<?php echo $post['id']; ?>" 
                                   class="btn" 
                                   style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                    <i class="uil uil-edit"></i>
                                </a>
                                <a href="delete-post.php?id=<?php echo $post['id']; ?>&from=admin" 
                                   class="btn" 
                                   style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background: var(--color-red);"
                                   onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="uil uil-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align: center; padding: 2rem;">No posts available.</p>
                <?php endif; ?>
            </main>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>