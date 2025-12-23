<?php require_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <style>
        .about-section {
            margin-top: 8rem;
            padding: 3rem 0;
        }
        .about-content {
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }
        .about-content h2 {
            margin-bottom: 1.5rem;
        }
        .about-content p {
            margin-bottom: 1.5rem;
            font-size: 1.05rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="about-section">
        <div class="container about-content">
            <h1>About Blogs of Nepal</h1>
            
            <p>Welcome to Blogs of Nepal, your premier destination for discovering the rich culture, breathtaking landscapes, and vibrant traditions of Nepal. We are passionate about sharing the stories, experiences, and insights that make Nepal a truly unique destination.</p>

            <h2>Our Mission</h2>
            <p>Our mission is to create a comprehensive platform where writers, travelers, and culture enthusiasts can share their experiences about Nepal. From ancient temples and sacred sites to trekking adventures and local cuisine, we cover it all.</p>

            <h2>What We Offer</h2>
            <p>At Blogs of Nepal, you'll find a diverse collection of articles covering various topics including temples and religious sites, travel destinations, local food and cuisine, cultural traditions, and much more. Our contributors are passionate individuals who have firsthand experience and deep knowledge about Nepal.</p>

            <h2>Join Our Community</h2>
            <p>We believe in the power of community and shared experiences. Whether you're a seasoned writer or just starting your blogging journey, we invite you to join our platform. Sign up today to share your stories, connect with fellow Nepal enthusiasts, and contribute to preserving and promoting Nepal's rich heritage.</p>

            <h2>Our Values</h2>
            <p>We are committed to authenticity, accuracy, and respect for Nepali culture and traditions. Every post on our platform is carefully reviewed to ensure quality content that educates, inspires, and entertains our readers.</p>

            <div style="margin-top: 3rem; text-align: center;">
                <a href="signup.php" class="btn">Join Our Community</a>
                <a href="contact.php" class="btn" style="background: var(--color-gray-700); margin-left: 1rem;">Contact Us</a>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>