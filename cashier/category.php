<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../includes/db.php');
require_once('../includes/functions.php');

// Redirect if user is not logged in or not an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'cashier') {
    header('Location: ../index.php');
    exit;
}

// Fetch categories from the database
$query = "SELECT * FROM categories";
$result = $mysqli->query($query);
$categories = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories - FRESHMART POS</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="sidebar">
        <!-- Sidebar content -->
    </div>
    <div class="main-content">
        <div class="header">
            <!-- Header content -->
        </div>
        <section>
            <h2>Categories</h2>
            <table>
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['category_id']; ?></td>
                            <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($category['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
