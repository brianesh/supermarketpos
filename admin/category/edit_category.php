<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = "";
$success = "";


if (!isset($_GET['category_id'])) {
    $error = "Category ID not provided.";
} else {
    $category_id = $_GET['category_id'];

    
    $query = "SELECT * FROM categories WHERE category_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $category = $result->fetch_assoc();
    } else {
        $error = "Category not found.";
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
   
    $name = $_POST['category_name'];
    $description = $_POST['description'];

    if (empty($name)) {
        $error = "Category name is required.";
    } else {
        
        $query = "UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?";
        $stmt = $mysqli->prepare($query);
        
        $stmt->bind_param("ssi", $name, $description, $category_id);
        
     
        if ($stmt->execute()) {
            $success = "Category updated successfully.";
            header('Location: index.php'); 
            exit;
        } else {
            $error = "Failed to update category.";
        }
        
        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<div class="sidebar">
<div class="profile-image">
        <h1><img src="../../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
    </div>
        <ul>
            <li><a href="../index.php">Dashboard</a></li>
            <li><a href="../store/index.php">Stores</a></li>
            <li><a href="../users/index.php">Users</a></li>
            <li><a href="../suppliers/index.php">Suppliers</a></li>
            <li><a href="index.php">Category</a></li>
            <li><a href="../products/index.php">Products</a></li>
            <li><a href="barcodescanner.php">Barcode Scanner</a></li>
            <li><a href="../reports/index.php">Reports</a></li>
            <li><a href="../expiredgoods.php">Expired Goods</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="time">
            <?php date_default_timezone_set('Africa/Nairobi');?>
                <?php echo date('l, F j, Y h:i A'); ?>
            </div>
            <div class="notifications">
            <a href="notifications.php"><i class="fa fa-bell"></i></a>
            </div>
        </div>
        <section>
            <h2>Edit Category</h2>
            <?php if (!empty($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if (isset($category)): ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?category_id=' . $category_id; ?>">
                    <label for="category_name">Category Name:</label>
                    <input type="text" id="category_name" name="category_name" required value="<?php echo htmlspecialchars($category['category_name']); ?>"><br><br>
                    <label for="description">Description:</label><br>
                    <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($category['description']); ?></textarea><br><br>
                    <button type="submit" name="update_category">Update Category</button>
                </form>
            <?php else: ?>
                <p>No category found.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
