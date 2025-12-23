<?php
require_once 'includes/functions.php';
requireLogin();

// Get user's posts
$stmt = $conn->prepare("SELECT p.*, c.name as category_name 
                        FROM posts p 
                        JOIN categories c ON p.category_id = c.id 
                        WHERE p.author_id = ? 
                        ORDER BY p.created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$myPosts = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Blogmandu</title>
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
                        <a href="add-post.php">
                            <i class="uil uil-plus"></i>
                            <h5>Add Post</h5>
                        </a>
                    </li>
                    <li>
                        <a href="dashboard.php" class="active">
                            <i class="uil uil-postcard"></i>
                            <h5>My Posts</h5>
                        </a>
                    </li>
                    <?php if (isAdmin()): ?>
                    <li>
                        <a href="admin-dashboard.php">
                            <i class="uil uil-user-check"></i>
                            <h5>Admin Panel</h5>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </aside>

            <main>
                <h2>My Posts</h2>
                
                <?php if ($myPosts->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($post = $myPosts->fetch_assoc()): ?>
                        <tr>
                            <td><a href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></td>
                            <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                            <td>
                                <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                    <i class="uil uil-edit"></i>
                                </a>
                                <a href="delete-post.php?id=<?php echo $post['id']; ?>" 
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
                <div style="text-align: center; padding: 3rem; background: var(--color-gray-900); border-radius: var(--card-border-radius-2);">
                    <p>You haven't created any posts yet.</p>
                    <a href="add-post.php" class="btn" style="margin-top: 1rem;">Create Your First Post</a>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>