<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = include('../../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact_name = $_POST['contact_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $product_name = $_POST['product_name'];

    $query = "INSERT INTO suppliers (name, contact_name, phone_number, email, address, product_name) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssss', $name, $contact_name, $phone_number, $email, $address, $product_name);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Supplier</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h1>Add New Supplier</h1>
    <form action="add_supplier.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="contact_name">Contact Name:</label>
        <input type="text" id="contact_name" name="contact_name" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>

        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required><br>

        <button type="submit">Add Supplier</button>
    </form>
</body>
</html>
