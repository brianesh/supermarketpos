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
