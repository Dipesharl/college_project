<?php
require_once 'config/database.php';

$username = 'admin';
$email = 'admin@blogmandu.com';
$password = 'admin123';
$full_name = 'Admin User';
$role = 'admin';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Delete existing admin
$conn->query("DELETE FROM users WHERE username = 'admin'");

// Insert new admin
$stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role, profile_image) VALUES (?, ?, ?, ?, ?, 'profile.jpeg')");
$stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $role);

if ($stmt->execute()) {
    echo "<h1>✅ Admin user created successfully!</h1>";
    echo "<p>Username: <strong>admin</strong></p>";
    echo "<p>Password: <strong>admin123</strong></p>";
    echo "<p><a href='signin.php'>Go to Sign In</a></p>";
} else {
    echo "<h1>❌ Error creating admin</h1>";
    echo "<p>" . $conn->error . "</p>";
}
?>
```

Save it (`Ctrl+O`, `Enter`, `Ctrl+X`)

Then visit:
```
http://localhost/blogmandu/create-admin.php