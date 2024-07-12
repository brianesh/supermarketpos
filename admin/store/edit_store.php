<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = include('../../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $company_name = $_POST['company_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $stmt = $mysqli->prepare("UPDATE company_details SET company_name = ?, phone = ?, email = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $company_name, $phone, $email, $address, $id);
    $stmt->execute();

    header("Location: index.php");
    exit();
} else {
    $id = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM company_details WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $store = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Store</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h1>Edit Store</h1>
    <form action="edit_store.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($store['id']); ?>">
        <label for="company_name">Company Name:</label>
        <input type="text" name="company_name" value="<?php echo htmlspecialchars($store['company_name']); ?>" required><br>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($store['phone']); ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($store['email']); ?>" required><br>
        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($store['address']); ?>" required><br>
        <button type="submit">Update Store</button>
    </form>
</body>
</html>

<?php
$mysqli->close();
?>
