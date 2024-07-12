<?php
session_start();
$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

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
            <a href="add_category.php">Add New Category</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category) : ?>
                        <tr>
                            <td><?php echo $category['category_id']; ?></td>
                            <td><?php echo $category['category_name']; ?></td>
                            <td>
                                <a href="edit_category.php?id=<?php echo $category['category_id']; ?>">Edit</a>
                                <a href="delete_category.php?id=<?php echo $category['category_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
