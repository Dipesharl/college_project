<?php
require_once 'config/database.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = "Please login to access this page";
        header("Location: signin.php");
        exit();
    }
}

// Redirect if not admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        $_SESSION['error'] = "Access denied. Admin only.";
        header("Location: index.php");
        exit();
    }
}

// Sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Get user by ID
function getUserById($conn, $id) {
    $stmt = $conn->prepare("SELECT id, username, email, full_name, role, profile_image FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get all posts with pagination
function getPosts($conn, $limit = 10, $offset = 0, $category_id = null, $search = null) {
    $sql = "SELECT p.*, u.full_name as author_name, u.profile_image as author_image, c.name as category_name 
            FROM posts p 
            JOIN users u ON p.author_id = u.id 
            JOIN categories c ON p.category_id = c.id 
            WHERE 1=1";
    
    $params = [];
    $types = "";
    
    if ($category_id) {
        $sql .= " AND p.category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }
    
    if ($search) {
        $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss";
    }
    
    $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Get featured post
function getFeaturedPost($conn) {
    $sql = "SELECT p.*, u.full_name as author_name, u.profile_image as author_image, c.name as category_name 
            FROM posts p 
            JOIN users u ON p.author_id = u.id 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.is_featured = 1 
            ORDER BY p.created_at DESC LIMIT 1";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Get post by ID
function getPostById($conn, $id) {
    $stmt = $conn->prepare("SELECT p.*, u.full_name as author_name, u.profile_image as author_image, c.name as category_name 
                           FROM posts p 
                           JOIN users u ON p.author_id = u.id 
                           JOIN categories c ON p.category_id = c.id 
                           WHERE p.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get all categories
function getCategories($conn) {
    $result = $conn->query("SELECT * FROM categories ORDER BY name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get total post count
function getTotalPosts($conn, $category_id = null, $search = null) {
    $sql = "SELECT COUNT(*) as total FROM posts WHERE 1=1";
    $params = [];
    $types = "";
    
    if ($category_id) {
        $sql .= " AND category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }
    
    if ($search) {
        $sql .= " AND (title LIKE ? OR content LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss";
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Format date
function formatDate($date) {
    return date('F j, Y - h:i A', strtotime($date));
}

// Truncate text
function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Handle file upload
function uploadImage($file, $oldImage = null) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF allowed.'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large. Maximum size is 5MB.'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $uploadPath = UPLOAD_PATH . $filename;
    
    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0777, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Delete old image if exists
        if ($oldImage && file_exists(UPLOAD_PATH . $oldImage)) {
            unlink(UPLOAD_PATH . $oldImage);
        }
        return ['success' => true, 'filename' => $filename];
    }
    
    return ['success' => false, 'message' => 'Failed to upload file.'];
}

// Delete image
function deleteImage($filename) {
    $filepath = UPLOAD_PATH . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
    }
}
?>