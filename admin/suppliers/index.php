<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

$query = "SELECT * FROM suppliers";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suppliers Dashboard</title>
    
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            <li><a href="">Suppliers</a></li>
            <li><a href="../category/index.php">Category</a></li>
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
    <table>
    <h1>Suppliers Dashboard</h1>
    <a href="add_supplier.php">Add New Supplier</a><br><br>
        <tr>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Phone Number</th>
            <th>Email Address</th>
            <th>Location</th>
            <th>Product Name</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
        <td><?php echo htmlspecialchars($row['supplier_id']); ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['address']); ?></td>
        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
        <td>
        <a href="edit_supplier.php?id=<?php echo $row['supplier_id']; ?>">Edit</a>
        <a href="delete_supplier.php?id=<?php echo $row['supplier_id']; ?>">Delete</a>
        </td>
        </tr>
        <?php endwhile; ?>
        </table>
        </div>
</body>
</html>
