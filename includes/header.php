<nav>
    <div class="container nav_container">
        <a href="index.php" class="nav_logo">Blogs of Nepal</a>
        <ul class="nav_items" id="nav_items">
            <li><a href="blog.php">Blogs</a></li>  
            <li><a href="about.php">About</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
            
            <?php if (isLoggedIn()): ?>
                <li class="nav_profile">
                    <div class="avatar">
                        <?php 
                        $profilePic = 'Image/default-avatar.jpg';
                        if (isset($_SESSION['profile_image'])) {
                            if (file_exists('uploads/' . $_SESSION['profile_image'])) {
                                $profilePic = 'uploads/' . $_SESSION['profile_image'];
                            } elseif (file_exists('Image/' . $_SESSION['profile_image'])) {
                                $profilePic = 'Image/' . $_SESSION['profile_image'];
                            }
                        }
                        ?>
                        <img src="<?php echo $profilePic; ?>" alt="Profile">
                    </div>
                    <ul>
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <?php if (isAdmin()): ?>
                            <li><a href="admin-dashboard.php">Admin Panel</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="signin.php">Sign In</a></li>
            <?php endif; ?>
        </ul>
        <button id="open_nav_btn"><i class="uil uil-bars"></i></button>
        <button id="close_nav_btn"><i class="uil uil-multiply"></i></button>
    </div>
</nav>