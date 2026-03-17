<?php require_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <style>
        .services-section {
            margin-top: 8rem;
            padding: 3rem 0;
        }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        .service-card {
            background: var(--color-gray-900);
            padding: 2rem;
            border-radius: var(--card-border-radius-2);
            text-align: center;
            transition: var(--transition);
            color:white;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.3);
        }
        .service-card i {
            font-size: 3rem;
            color: var(--color-primary);
            margin-bottom: 1rem;
        }
        .service-card h3 {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="services-section">
        <div class="container">
            <h1 style="text-align: center; margin-bottom: 1rem;">Our Services</h1>
            <p style="text-align: center; max-width: 600px; margin: 0 auto 3rem;">
                Discover the comprehensive services we offer to make your blogging experience seamless and enjoyable.
            </p>

            <div class="services-grid">
                <div class="service-card">
                    <i class="uil uil-pen"></i>
                    <h3>Content Publishing</h3>
                    <p>Create and publish engaging blog posts about Nepal's culture, travel destinations, and cuisine. Our easy-to-use platform makes publishing simple and efficient.</p>
                </div>

                <div class="service-card">
                    <i class="uil uil-image-upload"></i>
                    <h3>Image Hosting</h3>
                    <p>Upload and host high-quality images for your blog posts. Share stunning photographs of Nepal's landscapes, temples, and cultural events.</p>
                </div>

                <div class="service-card">
                    <i class="uil uil-users-alt"></i>
                    <h3>Community Engagement</h3>
                    <p>Connect with fellow writers and Nepal enthusiasts. Build your audience and engage with readers who share your passion for Nepal.</p>
                </div>

                <div class="service-card">
                    <i class="uil uil-list-ul"></i>
                    <h3>Category Organization</h3>
                    <p>Organize your content by categories including Temples, Travel, Food, and more. Help readers easily find content they're interested in.</p>
                </div>

                <div class="service-card">
                    <i class="uil uil-chart-line"></i>
                    <h3>Dashboard Analytics</h3>
                    <p>Track your posts and manage your content from a personalized dashboard. Monitor your blogging activity and engagement.</p>
                </div>

                <div class="service-card">
                    <i class="uil uil-shield-check"></i>
                    <h3>Secure Platform</h3>
                    <p>Your content and personal information are protected with industry-standard security measures. Blog with confidence and peace of mind.</p>
                </div>
            </div>

            <div style="text-align: center; margin-top: 4rem;">
                <h2>Ready to Get Started?</h2>
                <p style="margin: 1rem 0 2rem;">Join our community today and start sharing your Nepal stories!</p>
                <a href="signup.php" class="btn">Sign Up Now</a>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>