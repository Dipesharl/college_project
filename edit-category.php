<?php
require_once 'includes/functions.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: manage-categories.php");
    exit();
}

$category_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    $_SESSION['error'] = "Category not found";
    header("Location: manage-categories.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    
    if (empty($name)) {
        $error = "Category name is required";
    } else {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $category_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Category updated successfully";
            header("Location: manage-categories.php");
            exit();
        } else {
            $error = "Failed to update category. Name may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - Blogmandu</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="form_section">
        <div class="container form_section_container">
            <h2>Edit Category</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <input type="text" name="name" placeholder="Category Name" required 
                       value="<?php echo htmlspecialchars($category['name']); ?>">
                
                <button type="submit" class="btn">Update Category</button>
                <a href="manage-categories.php" class="btn" style="background: var(--color-gray-700); text-align: center;">Cancel</a>
            </form>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>