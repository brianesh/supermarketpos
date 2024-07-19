<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<div class="sidebar">
<div class="profile-image">
        <h1><img src="../../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
    </div>
        <ul>
            <li><a href="../index.php">Dashboard</a></li>
            <li><a href="">Stores</a></li>
            <li><a href="../users/index.php">Users</a></li>
            <li><a href="../suppliers/index.php">Suppliers</a></li>
            <li><a href="category.php">Category</a></li>
            <li><a href="../products/index.php">Products</a></li>
            <li><a href="barcodescanner.php">Barcode Scanner</a></li>
            <li><a href="../reports/index.php">Reports</a></li>
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

    <main>
        <section>
            <h2>Reports</h2>
            <ul>
                <li><a href="sales_report.php">Sales Report</a></li>
                <li><a href="inventory_report.php">Inventory Report</a></li>
                <li><a href="procurement_report.php">Procurement Report</a></li>
                <li><a href="user_report.php">User Report</a></li>
            </ul>
        </section>
    </main>
    </div>
</body>
</html>
