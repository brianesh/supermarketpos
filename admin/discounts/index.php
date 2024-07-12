<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Include database connection
require_once '../../includes/db.php';

// Fetch discounts data from database (example)
$sql = "SELECT * FROM discounts ORDER BY start_date DESC";
$result = $mysqli->query($sql);

// Check if query was successful
if (!$result) {
    $_SESSION['error'] = 'Failed to fetch discounts data.';
    header('Location: index.php'); // Redirect to discounts index or handle error
    exit;
}

// Fetch all rows into an associative array
$discounts = $result->fetch_all(MYSQLI_ASSOC);

// Close result set and database connection
$result->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discounts - Supermarket POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Discounts</h1>
        <nav>
            <ul>
                <li><a href="add_discount.php">Add New Discount</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Existing Discounts</h2>
            <table>
                <tr>
                    <th>Discount ID</th>
                    <th>Title</th>
                    <th>Product ID</th>
                    <th>Percentage</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($discounts as $discount): ?>
                <tr>
                    <td><?php echo $discount['id']; ?></td>
                    <td><?php echo $discount['title']; ?></td>
                    <td><?php echo $discount['product_id']; ?></td>
                    <td><?php echo $discount['percentage']; ?></td>
                    <td><?php echo $discount['start_date']; ?></td>
                    <td><?php echo $discount['end_date']; ?></td>
                    <td>
                        <a href="edit_discount.php?id=<?php echo $discount['id']; ?>">Edit</a>
                        <a href="delete_discount.php?id=<?php echo $discount['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Supermarket POS. All rights reserved.</p>
    </footer>
</body>
</html>
