<!-- index.php -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

$query = "SELECT * FROM company_details";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stores Dashboard</title>
    <!-- Include any necessary CSS stylesheets -->
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
    <table>
    <h1>Stores Dashboard</h1>
    <a href="add_store.php">Add New Store</a><br><br>
        <?php while ($row = $result->fetch_assoc()): ?>
        <?php echo htmlspecialchars($row['company_name']); ?>
        <?php echo htmlspecialchars($row['phone']); ?>
        <?php echo htmlspecialchars($row['email']); ?>
        <?php echo htmlspecialchars($row['address']); ?>
        <a href="edit_store.php?id=<?php echo $row['id']; ?>">Edit</a>
        <a href="delete_store.php?id=<?php echo $row['id']; ?>">Delete</a>
        <?php endwhile; ?>
        </table>
        </div>
</body>
</html>

<?php
// Close database connection
$mysqli->close();
?>
