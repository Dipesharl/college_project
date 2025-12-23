<?php
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: blog.php");
    exit();
}

$category_id = intval($_GET['id']);

// Get category name
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    header("Location: blog.php");
    exit();
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

$posts = getPosts($conn, $limit, $offset, $category_id);
$totalPosts = getTotalPosts($conn, $category_id);
$totalPages = ceil($totalPosts / $limit);
$categories = getCategories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="posts" style="margin-top: 8rem;">
        <div class="container">
            <h2>Category: <?php echo htmlspecialchars($category['name']); ?></h2>
            <p style="margin-bottom: 2rem;">Showing <?php echo $totalPosts; ?> post(s)</p>
        </div>

        <div class="container posts_container">
            <?php if ($posts->num_rows > 0): ?>
                <?php while ($post = $posts->fetch_assoc()): ?>
                <article class="post">
                    <div class="post_thumbnail">
                        <img src="<?php echo $post['image'] ? 'uploads/' . $post['image'] : 'Image/default.jpg'; ?>" 
                        alt="<?php echo htmlspecialchars($post['title']); ?>">
                    </div>
                    <div class="post_info">
                        <a href="category.php?id=<?php echo $post['category_id']; ?>" class="category_button">
                            <?php echo htmlspecialchars($post['category_name']); ?>
                        </a>  
                        <h3 class="post_title">
                            <a href="post.php?id=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <p class="post_body">
                            <?php echo truncateText($post['content'], 200); ?>
                        </p>
                        <div class="post_author">
                            <div class="post_author_avatar">
                                <?php 
                                $authorImage = 'Image/default-avatar.jpg';
                                if ($post['author_image']) {
                                    if (file_exists('uploads/' . $post['author_image'])) {
                                        $authorImage = 'uploads/' . htmlspecialchars($post['author_image']);
                                    } elseif (file_exists('Image/' . $post['author_image'])) {
                                        $authorImage = 'Image/' . htmlspecialchars($post['author_image']);
                                    }
                                }
                                ?>
                                <img src="<?php echo $authorImage; ?>" 
                                     alt="<?php echo htmlspecialchars($post['author_name']); ?>">
                            </div>
                            <div class="post_author_info">
                                <h5>By: <?php echo htmlspecialchars($post['author_name']); ?></h5>
                                <small><?php echo formatDate($post['created_at']); ?></small>
                            </div>
                        </div>
                    </div>  
                </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; padding: 3rem;">No posts found in this category.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="container" style="margin-top: 3rem; text-align: center;">
            <div style="display: inline-flex; gap: 0.5rem;">
                <?php if ($page > 1): ?>
                    <a href="?id=<?php echo $category_id; ?>&page=<?php echo $page - 1; ?>" class="btn">Previous</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?id=<?php echo $category_id; ?>&page=<?php echo $i; ?>" 
                       class="btn"
                       style="<?php echo $i === $page ? 'background: var(--color-white); color: var(--color-bg);' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?id=<?php echo $category_id; ?>&page=<?php echo $page + 1; ?>" class="btn">Next</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- Category Buttons -->
    <section class="category_buttons">
        <div class="container category_buttons_container">
            <a href="blog.php" class="category_button">All Posts</a>
            <?php foreach ($categories as $cat): ?>
                <a href="category.php?id=<?php echo $cat['id']; ?>" class="category_button">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>