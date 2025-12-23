<?php
require_once 'includes/functions.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required";
    } elseif (!validateEmail($email)) {
        $error = "Invalid email address";
    } else {
        // In a real application, you would send an email or save to database
        $success = "Thank you for contacting us! We'll get back to you soon.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <style>
        .contact-section {
            margin-top: 8rem;
            padding: 3rem 0;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin-top: 3rem;
        }
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        .contact-item i {
            font-size: 1.5rem;
            color: var(--color-primary);
            margin-top: 0.2rem;
        }
        @media screen and (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="contact-section">
        <div class="container">
            <h1 style="text-align: center; margin-bottom: 1rem;">Contact Us</h1>
            <p style="text-align: center; max-width: 600px; margin: 0 auto;">
                Have questions or feedback? We'd love to hear from you. Reach out to us using the form below.
            </p>

            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    
                    <div class="contact-item">
                        <i class="uil uil-map-marker"></i>
                        <div>
                            <h4>Address</h4>
                            <p>Kathmandu, Nepal<br>Bagmati Province</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="uil uil-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>info@blogsofnepal.com<br>support@blogsofnepal.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="uil uil-phone"></i>
                        <div>
                            <h4>Phone</h4>
                            <p>+977 1-XXXXXXX<br>+977 98XXXXXXXX</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="uil uil-clock"></i>
                        <div>
                            <h4>Office Hours</h4>
                            <p>Sunday - Friday: 10:00 AM - 6:00 PM<br>Saturday: Closed</p>
                        </div>
                    </div>

                    <div style="margin-top: 1rem;">
                        <h4>Follow Us</h4>
                        <div class="footer_socials" style="margin: 1rem 0; width: auto; justify-content: flex-start;">
                            <a href="https://facebook.com/" target="_blank"><i class="uil uil-facebook-f"></i></a>
                            <a href="https://twitter.com/" target="_blank"><i class="uil uil-twitter"></i></a>
                            <a href="https://instagram.com/" target="_blank"><i class="uil uil-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <div>
                    <h2>Send us a Message</h2>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form action="" method="POST" style="margin-top: 1.5rem;">
                        <input type="text" name="name" placeholder="Your Name" required 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        
                        <input type="email" name="email" placeholder="Your Email" required 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        
                        <input type="text" name="subject" placeholder="Subject" required 
                               value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                        
                        <textarea name="message" rows="6" placeholder="Your Message" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>