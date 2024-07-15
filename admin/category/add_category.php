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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    // Validate and sanitize inputs
    $name = $_POST['category_name'];
    $description = $_POST['description'];

    if (empty($name)) {
        $error = "Category name is required.";
    } else {
        // Prepare the SQL query using placeholders
        $query = "INSERT INTO categories (category_name, description) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);
        
        // Bind parameters to the prepared statement
        $stmt->bind_param("ss", $name, $description);
        
        // Execute the query
        if ($stmt->execute()) {
            $success = "Category added successfully.";
            header('Location: index.php'); // Redirect upon successful insertion
            exit;
        } else {
            $error = "Failed to add category.";
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
    <title>Add Category - FRESHMART POS</title>
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
            <h2>Add Category</h2>
            <?php if (!empty($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p><?php echo $success; ?></p>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" required><br><br>
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>
                <button type="submit" name="add_category">Add Category</button>
            </form>
        </section>
    </div>
</body>
</html>
