<?php
require_once 'includes/functions.php';
requireLogin();

$error = '';
$success = '';

// Get current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate full name
    if (empty($full_name)) {
        $error = "Full name is required";
    } else {
        $profile_image = $user['profile_image'];
        
        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $file_type = $_FILES['profile_image']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                $error = "Only JPG, PNG, and GIF images are allowed";
            } elseif ($_FILES['profile_image']['size'] > 5000000) { // 5MB limit
                $error = "Image size should not exceed 5MB";
            } else {
                $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = 'uploads/' . $new_filename;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                    // Delete old profile image if it's not a default image
                    if ($user['profile_image'] && $user['profile_image'] !== 'default-avatar.jpg' && !in_array($user['profile_image'], ['profile.jpeg', 'person.jpg', 'women.jpg'])) {
                        if (file_exists('uploads/' . $user['profile_image'])) {
                            unlink('uploads/' . $user['profile_image']);
                        }
                    }
                    $profile_image = $new_filename;
                } else {
                    $error = "Failed to upload profile image";
                }
            }
        }
        
        // Handle password change if provided
        if (!empty($error)) {
            // Error already set from image upload
        } elseif (!empty($new_password)) {
            // Verify current password
            if (empty($current_password)) {
                $error = "Current password is required to set a new password";
            } elseif (!password_verify($current_password, $user['password'])) {
                $error = "Current password is incorrect";
            } elseif (strlen($new_password) < 6) {
                $error = "New password must be at least 6 characters";
            } elseif ($new_password !== $confirm_password) {
                $error = "New passwords do not match";
            } else {
                // Update with new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET full_name = ?, profile_image = ?, password = ? WHERE id = ?");
                $stmt->bind_param("sssi", $full_name, $profile_image, $hashed_password, $_SESSION['user_id']);
                
                if ($stmt->execute()) {
                    $_SESSION['full_name'] = $full_name;
                    $_SESSION['profile_image'] = $profile_image;
                    $success = "Profile and password updated successfully!";
                    // Refresh user data
                    $user['full_name'] = $full_name;
                    $user['profile_image'] = $profile_image;
                } else {
                    $error = "Failed to update profile";
                }
            }
        } else {
            // Update without password change
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, profile_image = ? WHERE id = ?");
            $stmt->bind_param("ssi", $full_name, $profile_image, $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                $_SESSION['full_name'] = $full_name;
                $_SESSION['profile_image'] = $profile_image;
                $success = "Profile updated successfully!";
                // Refresh user data
                $user['full_name'] = $full_name;
                $user['profile_image'] = $profile_image;
            } else {
                $error = "Failed to update profile";
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
    <title>My Profile - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <style>
        .profile-picture-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--color-primary);
            margin: 1rem auto;
            display: block;
        }
        .profile-info {
            text-align: center;
            margin-bottom: 2rem;
        }
        .password-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--color-gray-900);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="form_section">
        <div class="container form_section_container">
            <h2>My Profile</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="profile-info">
                <?php 
                $profilePicPath = 'Image/default-avatar.jpg';
                if ($user['profile_image']) {
                    if (file_exists('uploads/' . $user['profile_image'])) {
                        $profilePicPath = 'uploads/' . htmlspecialchars($user['profile_image']);
                    } elseif (file_exists('Image/' . $user['profile_image'])) {
                        $profilePicPath = 'Image/' . htmlspecialchars($user['profile_image']);
                    }
                }
                ?>
                <img src="<?php echo $profilePicPath; ?>" alt="Profile Picture" class="profile-picture-preview" id="preview">
                <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p style="color: var(--color-gray-200);">@<?php echo htmlspecialchars($user['username']); ?></p>
                <p style="color: var(--color-gray-200);"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="full_name" placeholder="Full Name" required 
                       value="<?php echo htmlspecialchars($user['full_name']); ?>">
                
                <label for="profile_image" style="color: var(--color-white); margin-bottom: 0.5rem; display: block;">
                    Profile Picture
                </label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" onchange="previewImage(event)">
                <small style="display: block; margin-top: 0.5rem; margin-bottom: 1rem; color: var(--color-gray-200);">
                    Leave empty to keep current picture. Max size: 5MB
                </small>
                
                <div class="password-section">
                    <h3>Change Password (Optional)</h3>
                    <small style="display: block; margin-bottom: 1rem; color: var(--color-gray-200);">
                        Leave password fields empty if you don't want to change your password
                    </small>
                    
                    <input type="password" name="current_password" placeholder="Current Password">
                    <input type="password" name="new_password" placeholder="New Password (min. 6 characters)">
                    <input type="password" name="confirm_password" placeholder="Confirm New Password">
                </div>
                
                <button type="submit" class="btn">Update Profile</button>
                <a href="dashboard.php" class="btn" style="background: var(--color-gray-700); text-align: center;">Cancel</a>
            </form>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
    <script src="js/main.js"></script>
</body>
</html>
