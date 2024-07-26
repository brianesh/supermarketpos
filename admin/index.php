<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../includes/db.php');
require_once('../includes/functions.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$todaysSales = todaysSales();
$expiredProducts = expiredProducts();
$Products = Products();
$suppliers = suppliers();
$users = users();
$Stores = Stores();
$getWeeksSales = getWeeksSales();
$getMonthsSales = getMonthsSales();
$getYearsSales = getYearsSales();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FRESHMART POS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-image">
            <h1><img src="../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
        </div>
        <ul>
            <li><a href="">Dashboard</a></li>
            <li><a href="store/index.php">Stores</a></li>
            <li><a href="users/index.php">Users</a></li>
            <li><a href="suppliers/index.php">Suppliers</a></li>
            <li><a href="category/index.php">Category</a></li>
            <li><a href="products/index.php">Products</a></li>
            <li><a href="barcodescanner.php">Barcode Scanner</a></li>
            <li><a href="reports/index.php">Reports</a></li>
            <li><a href="expiredgoods.php">Expired Goods</a></li>
            <li><a href="logout.php">Logout</a></li>
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
            <h2>Dashboard</h2>
            <div class="user">
                Welcome back, <?php echo $_SESSION['username']; ?> (Admin)
            </div>
            <div class="overview">
                <div class="card">
                    <h3>Today's Sales</h3>
                    <p>Ksh <?php echo number_format($todaysSales, 2); ?></p>
                    <a href="sales/index.php">View Sales</a>
                </div>
                <div class="card">
                    <h3>Expired Products</h3>
                    <p><?php echo $expiredProducts; ?></p>
                    <a href="expiredgoods/">View Expired Products</a>
                </div>
                <div class="card">
                    <h3>Products</h3>
                    <p><?php echo $Products; ?></p>
                    <a href="products/">View Products</a>
                </div>
                <div class="card">
                    <h3>Suppliers</h3>
                    <p><?php echo $suppliers; ?></p>
                    <a href="suppliers/">View Suppliers</a>
                </div>
                <div class="card">
                    <h3>Users</h3>
                    <p><?php echo $users; ?></p>
                    <a href="users/">View Users</a>
                </div>
                <div class="card">
                    <h3>Week's Sales</h3>
                    <p>Ksh <?php echo number_format($getWeeksSales, 2); ?></p>
                </div>
                <div class="card">
                    <h3>Month's Sales</h3>
                    <p>Ksh <?php echo number_format($getMonthsSales, 2); ?></p>
                </div>
                <div class="card">
                    <h3>Year's Sales</h3>
                    <p>Ksh <?php echo number_format($getYearsSales, 2); ?></p>
                </div>
                <div class="card">
                    <h3>Stores</h3>
                    <p><?php echo $Stores; ?></p>
                    <a href="stores/">View Stores</a>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
