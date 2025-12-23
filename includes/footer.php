<footer>
    <div class="footer_socials">
        <a href="https://facebook.com/" target="_blank"><i class="uil uil-facebook-f"></i></a>
        <a href="https://twitter.com/" target="_blank"><i class="uil uil-twitter"></i></a>
        <a href="https://instagram.com/" target="_blank"><i class="uil uil-instagram"></i></a>
        <a href="https://linkedin.com/" target="_blank"><i class="uil uil-linkedin-alt"></i></a>
        <a href="https://youtube.com/" target="_blank"><i class="uil uil-youtube"></i></a>
    </div>
   
    <div class="container footer_container">
        <article>
            <h4>Categories</h4>
            <ul>
                <?php
                $categories = getCategories($conn);
                foreach ($categories as $category):
                ?>
                    <li><a href="category.php?id=<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a></li>
                <?php endforeach; ?>
            </ul>
        </article>
        <article>
            <h4>Support</h4>
            <ul>
                <li><a href="contact.php">Online Support</a></li>
                <li><a href="contact.php">Call Numbers</a></li>
                <li><a href="contact.php">Emails</a></li>
                <li><a href="#">Social Support</a></li>
                <li><a href="contact.php">Location</a></li>
            </ul>
        </article>
        <article>
            <h4>Blogs-Of-Nep</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </article>
    </div>
    <div class="footer_copyright">
        <small>Copyright &copy; <?php echo date('Y'); ?> Blogs of Nepal</small>
    </div>
</footer>