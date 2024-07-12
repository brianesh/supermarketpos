<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}
$user_id = null;
$user = null;
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "SELECT * FROM users WHERE user_id='$user_id'";
    $result = $mysqli->query($query);
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found";
    }
} else {
    echo "User ID not provided";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Update user details in the database
    $query = "UPDATE users SET full_name='$full_name', email='$email', phone='$phone', username='$username', role='$role' WHERE user_id='$user_id'";

    if ($mysqli->query($query) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error updating user: " . $mysqli->error;
    }
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="sidebar">
    <h2>FRESHMART POS</h2>
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
                <a href="notifications.php">Notifications</a>
            </div>
        </div>
        <section>
            <h2>Edit User</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required><br><br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required><br><br>

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="cashier" <?php echo ($user['role'] === 'cashier') ? 'selected' : ''; ?>>Cashier</option>
                </select><br><br>

                <button type="submit" name="edit_user">Save Changes</button>
            </form>
        </section>
    </div>
</body>
</html>
