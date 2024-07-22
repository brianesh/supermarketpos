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


if (!isset($_SESSION['user_id'])) {
    die('User ID is not set in the session.');
}

$cashier_id = $_SESSION['user_id'];

$todaysSales = todaysSales($cashier_id);
$expiredProducts = expiredProducts();
$Products = Products();
$getWeeksSales = getWeeksSales($cashier_id);
$getMonthsSales = getMonthsSales($cashier_id);
$getYearsSales = getYearsSales($cashier_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard - FRESHMART POS</title>
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
            <li><a href="category.php">Category</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="pos/index.php">POS</a></li>
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
            <h2>Dashboard</h2>
            <div class="user">
                Welcome back, <?php echo $_SESSION['username']; ?> (Cashier)
            </div>
            <div class="overview">
                <div class="card">
                    <h3>Today's Sales</h3>
                    <p>Ksh <?php echo number_format($todaysSales, 2); ?></p>
                    <a href="../sales/index.php">View Sales</a>
                </div>
                <div class="card">
                    <h3>Expired Products</h3>
                    <p><?php echo $expiredProducts; ?></p>
                    <a href="../expiredgoods/">View Expired Products</a>
                </div>
                <div class="card">
                    <h3>Products</h3>
                    <p><?php echo $Products; ?></p>
                    <a href="../products/">View Products</a>
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
            </div>
        </section>
    </div>
</body>
</html>
