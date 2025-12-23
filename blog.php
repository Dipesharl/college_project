<?php
require_once 'includes/functions.php';

$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : null;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

$posts = getPosts($conn, $limit, $offset, $category_id, $search);
$totalPosts = getTotalPosts($conn, $category_id, $search);
$totalPages = ceil($totalPosts / $limit);
$categories = getCategories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Blogs - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="posts" style="margin-top: 8rem;">
        <div class="container">
            <h2>All Blog Posts</h2>
            
            <!-- Search Form -->
            <form action="" method="GET" style="margin-bottom: 2rem;">
                <div style="display: flex; gap: 1rem;">
                    <input type="text" name="search" placeholder="Search posts..." 
                           value="<?php echo htmlspecialchars($search ?? ''); ?>"
                           style="flex: 1; padding: 0.8rem; background: var(--color-gray-900); border-radius: var(--card-border-radius-2); color: var(--color-white);">
                    <button type="submit" class="btn">Search</button>
                    <?php if ($search): ?>
                        <a href="blog.php" class="btn" style="background: var(--color-gray-700);">Clear</a>
                    <?php endif; ?>
                </div>
            </form>

            <?php if ($search): ?>
                <p style="margin-bottom: 2rem;">Showing results for: <strong><?php echo htmlspecialchars($search); ?></strong></p>
            <?php endif; ?>
        </div>

        <div class="container posts_container">
            <?php if ($posts->num_rows > 0): ?>
                <?php while ($post = $posts->fetch_assoc()): ?>
                <article class="post">
                    <div class="post_thumbnail">
                        <?php 
                        $imagePath = 'Image/default.jpg';
                        if ($post['image']) {
                            if (file_exists('uploads/' . $post['image'])) {
                                $imagePath = 'uploads/' . $post['image'];
                            } elseif (file_exists('Image/' . $post['image'])) {
                                $imagePath = 'Image/' . $post['image'];
                            }
                        }
                        ?>
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
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
                <p style="text-align: center; padding: 3rem;">No posts found.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="container" style="margin-top: 3rem; text-align: center;">
            <div style="display: inline-flex; gap: 0.5rem;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?>" 
                       class="btn">Previous</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?>" 
                       class="btn <?php echo $i === $page ? 'active' : ''; ?>"
                       style="<?php echo $i === $page ? 'background: var(--color-white); color: var(--color-bg);' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category_id ? '&category=' . $category_id : ''; ?>" 
                       class="btn">Next</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- Category Buttons -->
    <section class="category_buttons">
        <div class="container category_buttons_container">
            <a href="blog.php" class="category_button">All Posts</a>
            <?php foreach ($categories as $category): ?>
                <a href="category.php?id=<?php echo $category['id']; ?>" class="category_button">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>