<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = include('../../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $stmt = $mysqli->prepare("INSERT INTO company_details (company_name, phone, email, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $company_name, $phone, $email, $address);
    $stmt->execute();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Store</title>
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
            <li><a href="../expiredgoods.php">Expired Goods</a></li>
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
    <h1>Add New Store</h1>
    <form action="add_store.php" method="POST">
        <label for="company_name">Company Name:</label>
        <input type="text" name="company_name" required><br>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        <label for="address">Address:</label>
        <input type="text" name="address" required><br>
        <button type="submit">Add Store</button>
    </form>
</body>
</html>

<?php
$mysqli->close();
?>

