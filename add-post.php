<?php
require_once 'includes/functions.php';
requireLogin();

$categories = getCategories($conn);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $category_id = intval($_POST['category_id']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    if (empty($title) || empty($content) || empty($category_id)) {
        $error = "Title, content, and category are required";
    } else {
        $image_filename = null;
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadImage($_FILES['image']);
            if ($upload_result['success']) {
                $image_filename = $upload_result['filename'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            // Only admins can set featured posts
            if (!isAdmin()) {
                $is_featured = 0;
            }
            
            // If setting as featured, unset all other featured posts first
            if ($is_featured == 1 && isAdmin()) {
                $conn->query("UPDATE posts SET is_featured = 0");
            }
            
            $stmt = $conn->prepare("INSERT INTO posts (title, content, category_id, author_id, image, is_featured) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiisi", $title, $content, $category_id, $_SESSION['user_id'], $image_filename, $is_featured);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Post created successfully!" . ($is_featured ? " This post is now featured." : "");
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Failed to create post. Please try again.";
                if ($image_filename) {
                    deleteImage($image_filename);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="form_section">
        <div class="container form_section_container" style="width: 60%;">
            <h2>Add New Post</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Post Title" required 
                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"
                                <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <textarea name="content" rows="10" placeholder="Post Content" required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                
                <input type="file" name="image" accept="image/*">
                
                <?php if (isAdmin()): ?>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                           <?php echo (isset($_POST['is_featured']) && $_POST['is_featured']) ? 'checked' : ''; ?>>
                    <label for="is_featured" style="color: var(--color-white); cursor: pointer;">
                        Set as Featured Post <small style="color: var(--color-gray-200);">(will replace current featured post)</small>
                    </label>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn">Publish Post</button>
                <a href="dashboard.php" class="btn" style="background: var(--color-gray-700); text-align: center;">Cancel</a>
            </form>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html> 