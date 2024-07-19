<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = include('../../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact_name = $_POST['contact_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $product_name = $_POST['product_name'];

    $query = "UPDATE suppliers SET name = ?, contact_name = ?, phone_number = ?, email = ?, address = ?, product_name = ? 
              WHERE supplier_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssssi', $name, $contact_name, $phone_number, $email, $address, $product_name, $id);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $id = $_GET['id'];
    $query = "SELECT * FROM suppliers WHERE supplier_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $supplier = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h1>Edit Supplier</h1>
    <form action="edit_supplier.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($supplier['supplier_id']); ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($supplier['name']); ?>" required><br>

        <label for="contact_name">Contact Name:</label>
        <input type="text" id="contact_name" name="contact_name" value="<?php echo htmlspecialchars($supplier['contact_name']); ?>" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($supplier['phone_number']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($supplier['email']); ?>" required><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($supplier['address']); ?>" required><br>

        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($supplier['product_name']); ?>" required><br>

        <button type="submit">Update Supplier</button>
    </form>
</body>
</html>
