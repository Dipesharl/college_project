<?php
require_once 'includes/functions.php';
requireAdmin();

// Handle category addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = sanitize($_POST['category_name']);
    
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Category added successfully";
        } else {
            $_SESSION['error'] = "Failed to add category. It may already exist.";
        }
        header("Location: manage-categories.php");
        exit();
    }
}

// Get all categories with post counts
$categories = $conn->query("SELECT c.*, COUNT(p.id) as post_count 
                           FROM categories c 
                           LEFT JOIN posts p ON c.id = p.category_id 
                           GROUP BY c.id 
                           ORDER BY c.name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Blogmandu</title>
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
                        <a href="manage-users.php">
                            <i class="uil uil-users-alt"></i>
                            <h5>Manage Users</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-categories.php" class="active">
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
                <h2>Manage Categories</h2>
                
                <!-- Add Category Form -->
                <div style="background: var(--color-gray-900); padding: 1.5rem; border-radius: var(--card-border-radius-2); margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1rem;">Add New Category</h3>
                    <form action="" method="POST" style="display: flex; gap: 1rem;">
                        <input type="text" name="category_name" placeholder="Category Name" required
                               style="flex: 1; padding: 0.8rem; background: var(--color-bg); border-radius: var(--card-border-radius-2); color: var(--color-gray-900);">
                        <button type="submit" name="add_category" class="btn">Add Category</button>
                    </form>
                </div>

                <h3>All Categories</h3>
                
                <?php if ($categories->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Number of Posts</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <a href="category.php?id=<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </td>
                            <td><?php echo $category['post_count']; ?> post(s)</td>
                            <td><?php echo date('M j, Y', strtotime($category['created_at'])); ?></td>
                            <td>
                                <a href="edit-category.php?id=<?php echo $category['id']; ?>" 
                                   class="btn" 
                                   style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                    <i class="uil uil-edit"></i>
                                </a>
                                <?php if ($category['post_count'] == 0): ?>
                                    <a href="delete-category.php?id=<?php echo $category['id']; ?>" 
                                       class="btn" 
                                       style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background: var(--color-red);"
                                       onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="uil uil-trash-alt"></i>
                                    </a>
                                <?php else: ?>
                                    <span style="font-size: 0.85rem; color: var(--color-gray-700);" title="Cannot delete category with posts">
                                        <i class="uil uil-lock"></i>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align: center; padding: 2rem;">No categories found.</p>
                <?php endif; ?>
            </main>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>