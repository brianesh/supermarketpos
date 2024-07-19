<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../includes/db.php');
require_once('../includes/functions.php');


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'cashier') {
    header('Location: ../index.php');
    exit;
}

$query = "SELECT * FROM categories";
$result = $mysqli->query($query);
$categories = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories - FRESHMART POS</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="sidebar">
        <div class="profile-image">
            <h1><img src="../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
        </div>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="">Category</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="pos.php">POS</a></li>
            <li><a href="expired_goods.php">Expired Goods</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="time">
                <?php echo date('l, F j, Y h:i A'); ?>
            </div>
            <div class="notifications">
                <a href="../notifications.php"><i class="fa fa-bell"></i></a>
            </div>
        </div>
        <section>
            <h2>Categories</h2>
            <table>
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['category_id']; ?></td>
                            <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($category['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
