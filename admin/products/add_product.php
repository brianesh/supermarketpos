<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "supermarketpos";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
   
    header('Location: ../../index.php');
    exit;
}


$company_name = isset($_SESSION['company_name']) ? $_SESSION['company_name'] : 'Your Company Name';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'];
    $category_name = $_POST['category_name'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $sku = $_POST['sku'];
    $barcode = $_POST['barcode'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $expiration_date = $_POST['expiration_date'];
    
    
    $stmt = $conn->prepare("INSERT INTO products (name, category_name, price, cost, sku, barcode, quantity, description, expiration_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $name, $category_name, $price, $cost, $sku, $barcode, $quantity, $description, $expiration_date);
    
    if ($stmt->execute()) {
        
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close(); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - <?php echo htmlspecialchars($company_name); ?> POS</title>
    <link rel="stylesheet" href="../../css/style.css">
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
            <li><a href="../suppliers/index.php">Suppliers</a></li>
            <li><a href="../category/index.php">Category</a></li>
            <li><a href="../products/index.php">Products</a></li>
            <li><a href="../barcodescanner.php">Barcode Scanner</a></li>
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

    <main>
        <section>
            <h2>Product Details</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" required><br><br>

                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required><br><br>

                <label for="cost">Cost:</label>
                <input type="text" id="cost" name="cost" required><br><br>

                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" required><br><br>

                <label for="barcode">Barcode:</label>
                <input type="text" id="barcode" name="barcode" required><br><br>

                <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity" required><br><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea><br><br>

                <label for="expiration_date">Expiration Date:</label>
                <input type="date" id="expiration_date" name="expiration_date" required><br><br>

                <button type="submit">Add Product</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($company_name); ?> POS. All rights reserved.</p>
    </footer>
</body>
</html>
