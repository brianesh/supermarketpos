<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

// Redirect if user is not logged in or not an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = "";
$success = "";

// Check if category ID is provided
if (!isset($_GET['category_id'])) {
    $error = "Category ID not provided.";
} else {
    $category_id = $_GET['category_id'];

    // Delete category from the database
    $query = "DELETE FROM categories WHERE category_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $category_id);
    
    if ($stmt->execute()) {
        $success = "Category deleted successfully.";
        header('Location: index.php');
        exit;
    } else {
        $error = "Failed to delete category.";
    }

    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Category - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<div class="sidebar">
<div class="profile-image">
        <h1><img src="../../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
    </div>
        <ul>
            <li><a href="../index.php">Dashboard</a></li>
            <li><a href="stores.php">Stores</a></li>
            <li><a href="">Users</a></li>
            <li><a href="suppliers/index.php">Suppliers</a></li>
            <li><a href="category.php">Category</a></li>
            <li><a href="../products/index.php">Products</a></li>
            <li><a href="barcodescanner.php">Barcode Scanner</a></li>
            <li><a href="reports/index.php">Reports</a></li>
            <li><a href="expiredgoods.php">Expired Goods</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="time">
                <?php echo date('l, F j, Y h:i A'); ?>
            </div>
            <div class="notifications">
            <a href="notifications.php"><i class="fa fa-bell"></i></a>
            </div>
        </div>
        <section>
            <h2>Delete Category</h2>
            <?php if (!empty($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p><?php echo $success; ?></p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
