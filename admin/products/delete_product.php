<?php
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

// Handle delete action
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Example of deleting product from database
    $sql = "DELETE FROM products WHERE product_id='$product_id'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to product list after deleting product
        header('Location: index.php');
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Product ID not provided";
    exit;
}

$conn->close(); // Close the database connection
?>
