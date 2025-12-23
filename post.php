    <?php
    require_once 'includes/functions.php';

    if (!isset($_GET['id'])) {
        header("Location: blog.php");
        exit();
    }

    $post_id = intval($_GET['id']);
    $post = getPostById($conn, $post_id);

    if (!$post) {
        header("Location: blog.php");
        exit();
    }

    // Get related posts from same category
    $relatedPosts = $conn->prepare("SELECT p.*, u.full_name as author_name, c.name as category_name 
                                    FROM posts p 
                                    JOIN users u ON p.author_id = u.id 
                                    JOIN categories c ON p.category_id = c.id 
                                    WHERE p.category_id = ? AND p.id != ? 
                                    ORDER BY RAND() LIMIT 3");
    $relatedPosts->bind_param("ii", $post['category_id'], $post_id);
    $relatedPosts->execute();
    $relatedPosts = $relatedPosts->get_result();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($post['title']); ?> - Blogmandu</title>
        <link rel="stylesheet" href="./style.css">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
        <style>
            .single-post {
                margin-top: 8rem;
            }
            .single-post-content {
                max-width: 800px;
                margin: 0 auto;
            }
            .single-post-image {
                width: 100%;
                height: 400px;
                object-fit: cover;
                border-radius: var(--card-border-radius-3);
                margin-bottom: 2rem;
            }
            .post-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid var(--color-gray-300);
            }
            .post-content {
                line-height: 1.8;
                font-size: 1.1rem;
                margin-bottom: 3rem;
            }
            .related-posts {
                margin-top: 4rem;
                padding-top: 2rem;
                border-top: 2px solid var(--color-gray-900);
            }
        </style>
    </head>
    <body>
        <?php include 'includes/header.php'; ?>

        <section class="single-post">
            <div class="container single-post-content">
                <a href="category.php?id=<?php echo $post['category_id']; ?>" class="category_button">
                    <?php echo htmlspecialchars($post['category_name']); ?>
                </a>
                
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                
                <div class="post-meta">
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
                            <h5><?php echo htmlspecialchars($post['author_name']); ?></h5>
                            <small><?php echo formatDate($post['created_at']); ?></small>
                        </div>
                    </div>
                    
                    <?php if (isLoggedIn() && ($_SESSION['user_id'] == $post['author_id'] || isAdmin())): ?>
                        <div>
                            <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                                <i class="uil uil-edit"></i> Edit
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($post['image']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" 
                        alt="<?php echo htmlspecialchars($post['title']); ?>" 
                        class="single-post-image">
                <?php endif; ?>

                <div class="post-content">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>

                <!-- Related Posts -->
                <?php if ($relatedPosts->num_rows > 0): ?>
                <div class="related-posts">
                    <h3>Related Posts</h3>
                    <div class="posts_container" style="margin-top: 2rem;">
                        <?php while ($related = $relatedPosts->fetch_assoc()): ?>
                        <article class="post">
                            <div class="post_thumbnail">
                                <img src="<?php echo $related['image'] ? 'uploads/' . $related['image'] : 'Image/default.jpg'; ?>" 
                                    alt="<?php echo htmlspecialchars($related['title']); ?>">
                            </div>
                            <div class="post_info">
                                <a href="category.php?id=<?php echo $related['category_id']; ?>" class="category_button">
                                    <?php echo htmlspecialchars($related['category_name']); ?>
                                </a>  
                                <h3 class="post_title">
                                    <a href="post.php?id=<?php echo $related['id']; ?>">
                                        <?php echo htmlspecialchars($related['title']); ?>
                                    </a>
                                </h3>
                            </div>  
                        </article>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <?php include 'includes/footer.php'; ?>
        <script src="js/main.js"></script>
    </body>
    </html>