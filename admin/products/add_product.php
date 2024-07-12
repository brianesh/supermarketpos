<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "supermarketpos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Fetch company name from session
$company_name = isset($_SESSION['company_name']) ? $_SESSION['company_name'] : 'Your Company Name';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example of processing form data
    $name = $_POST['name'];
    $category_name = $_POST['category_name'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $sku = $_POST['sku'];
    $barcode = $_POST['barcode'];
    $description = $_POST['description'];
    $expiration_date = $_POST['expiration_date'];
    
    // Example of inserting into database with prepared statement
    $stmt = $conn->prepare("INSERT INTO products (name, category_name, price, cost, sku, barcode, description, expiration_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $category_name, $price, $cost, $sku, $barcode, $description, $expiration_date);
    
    if ($stmt->execute()) {
        // Redirect to product list after adding product
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close(); // Close the database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - <?php echo htmlspecialchars($company_name); ?> POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Add New Product</h1>
        <nav>
            <ul>
                <li><a href="index.php">Product List</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

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
