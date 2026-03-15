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
    $_SESSION['error'] = "You don't have permission to edit this post";
    header("Location: dashboard.php");
    exit();
}

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
        $image_filename = $post['image'];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadImage($_FILES['image'], $post['image']);
            if ($upload_result['success']) {
                $image_filename = $upload_result['filename'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            // Only admins can set featured posts
            if (!isAdmin()) {
                $is_featured = $post['is_featured']; // Keep existing value
            } else {
                // If setting as featured, unset all other featured posts first
                if ($is_featured == 1) {
                    $conn->query("UPDATE posts SET is_featured = 0 WHERE id != $post_id");
                }
            }
            
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, category_id = ?, image = ?, is_featured = ? WHERE id = ?");
            $stmt->bind_param("ssisii", $title, $content, $category_id, $image_filename, $is_featured, $post_id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Post updated successfully!" . ($is_featured ? " This post is now featured." : "");
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Failed to update post. Please try again.";
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
    <title>Edit Post - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="form_section">
        <div class="container form_section_container" style="width: 60%;">
            <h2>Edit Post</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Post Title" required 
                       value="<?php echo htmlspecialchars($post['title']); ?>">
                
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"
                                <?php echo $post['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <textarea name="content" rows="10" placeholder="Post Content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                
                <?php if ($post['image']): ?>
                    <div style="margin-bottom: 1rem;">
                        <?php 
                        $currentImage = 'Image/default.jpg';
                        if ($post['image']) {
                            if (file_exists('uploads/' . $post['image'])) {
                                $currentImage = 'uploads/' . htmlspecialchars($post['image']);
                            } elseif (file_exists('Image/' . $post['image'])) {
                                $currentImage = 'Image/' . htmlspecialchars($post['image']);
                            }
                        }
                        ?>
                        <img src="<?php echo $currentImage; ?>" 
                             alt="Current image" 
                             style="max-width: 200px; border-radius: var(--card-border-radius-2);">
                        <p style="margin-top: 0.5rem; font-size: 0.85rem;">Current image</p>
                    </div>
                <?php endif; ?>
                
                <input type="file" name="image" accept="image/*">
                <small style="display: block; margin-top: -0.5rem; margin-bottom: 0.8rem; color: var(--color-gray-200);">
                    Leave empty to keep current image
                </small>
                
                <?php if (isAdmin()): ?>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                           <?php echo $post['is_featured'] ? 'checked' : ''; ?>>
                    <label for="is_featured" style="color: var(--color-gray-700); cursor: pointer;">
                        Set as Featured Post <small style="color: var(--color-gray-200);">(will replace current featured post)</small>
                    </label>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn">Update Post</button>
                <a href="dashboard.php" class="btn" style="background: var(--color-gray-700); text-align: center;">Cancel</a>
            </form>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>