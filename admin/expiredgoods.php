<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = include('../includes/db.php');

// Get the current date
$current_date = date('Y-m-d');

// Query to select products with an expiration date that is the current date or has passed
$query = "SELECT * FROM products WHERE expiration_date <= ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $current_date);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expired Goods</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="sidebar">
    <div class="profile-image">
        <h1><img src="../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
    </div>
    <ul>
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="store/index.php">Stores</a></li>
        <li><a href="users/index.php">Users</a></li>
        <li><a href="suppliers/index.php">Suppliers</a></li>
        <li><a href="category/index.php">Category</a></li>
        <li><a href="products/index.php">Products</a></li>
        <li><a href="barcodescanner.php">Barcode Scanner</a></li>
        <li><a href="reports/index.php">Reports</a></li>
        <li><a href="">Expired Goods</a></li>
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
    <h1>Expired Goods</h1>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Expiration Date</th>
            <th>Quantity</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                <td><?php echo htmlspecialchars($row['expiration_date']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
