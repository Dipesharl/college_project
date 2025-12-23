<?php
require_once 'includes/functions.php';

// Get featured post
$featuredPost = getFeaturedPost($conn);

// Get latest posts (excluding featured)
$posts = getPosts($conn, 4, 0);

// Get categories
$categories = getCategories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogmandu - Blogs of Nepal</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head> 
<body>
    <?php include 'includes/header.php'; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success container" style="margin-top: 6rem;">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger container" style="margin-top: 6rem;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Category Buttons - NOW BELOW HEADER -->
    <section class="category_buttons" style="margin-top: 6rem; padding: 2rem 0;">
        <div class="container category_buttons_container">
            <a href="blog.php" class="category_button">All Posts</a>
            <?php foreach ($categories as $category): ?>
                <a href="category.php?id=<?php echo $category['id']; ?>" class="category_button">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Featured Post -->
    <?php if ($featuredPost): ?>
    <section class="featured">
        <div class="container featured_container">
            <div class="post_thumbnail">
                <?php 
                $imagePath = 'Image/default.jpg';
                if ($featuredPost['image']) {
                    if (file_exists('uploads/' . $featuredPost['image'])) {
                        $imagePath = 'uploads/' . htmlspecialchars($featuredPost['image']);
                    } elseif (file_exists('Image/' . $featuredPost['image'])) {
                        $imagePath = 'Image/' . htmlspecialchars($featuredPost['image']);
                    }
                }
                ?>
                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($featuredPost['title']); ?>">
            </div>
            <div class="post_info">
                <a href="category.php?id=<?php echo $featuredPost['category_id']; ?>" class="category_button">
                    <?php echo htmlspecialchars($featuredPost['category_name']); ?>
                </a>
                <h2 class="post_title">
                    <a href="post.php?id=<?php echo $featuredPost['id']; ?>">
                        <?php echo htmlspecialchars($featuredPost['title']); ?>
                    </a>
                </h2>
                <p class="post_body">
                    <?php echo truncateText($featuredPost['content'], 400); ?>
                </p>
                <div class="post_author">
                    <div class="post_author_avatar">
                        <?php 
                        // Smart path detection for author profile image
                        $authorImage = 'Image/default-avatar.jpg';
                        if ($featuredPost['author_image']) {
                            if (file_exists('uploads/' . $featuredPost['author_image'])) {
                                $authorImage = 'uploads/' . htmlspecialchars($featuredPost['author_image']);
                            } elseif (file_exists('Image/' . $featuredPost['author_image'])) {
                                $authorImage = 'Image/' . htmlspecialchars($featuredPost['author_image']);
                            }
                        }
                        ?>
                        <img src="<?php echo $authorImage; ?>" 
                             alt="<?php echo htmlspecialchars($featuredPost['author_name']); ?>">
                    </div>
                    <div class="post_author_info">
                        <h5><?php echo htmlspecialchars($featuredPost['author_name']); ?></h5>
                        <small><?php echo formatDate($featuredPost['created_at']); ?></small>
                    </div>
                </div>
            </div>  
        </div>
    </section>
    <?php endif; ?>

    <!-- Latest Posts -->
    <section class="posts">
        <div class="container posts_container">
            <?php while ($post = $posts->fetch_assoc()): ?>
                <?php if (!$featuredPost || $post['id'] != $featuredPost['id']): ?>
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
                                // Smart path detection for author profile image
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
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body> 
</html>