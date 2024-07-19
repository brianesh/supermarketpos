<?php
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


if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
   
    $sql = "DELETE FROM products WHERE product_id='$product_id'";
    
    if ($conn->query($sql) === TRUE) {
        
        header('Location: index.php');
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Product ID not provided";
    exit;
}

$conn->close(); 
?>
