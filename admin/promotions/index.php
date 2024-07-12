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

// Fetch promotions data from database (example)
$sql = "SELECT * FROM promotions ORDER BY start_date DESC";
$result = $mysqli->query($sql);

// Check if query was successful
if (!$result) {
    $_SESSION['error'] = 'Failed to fetch promotions data.';
    header('Location: index.php'); // Redirect to promotions index or handle error
    exit;
}

// Fetch all rows into an associative array
$promotions = $result->fetch_all(MYSQLI_ASSOC);

// Close result set and database connection
$result->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Promotions - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Promotions</h1>
        <nav>
            <ul>
                <li><a href="add_promotion.php">Add New Promotion</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Existing Promotions</h2>
            <table>
                <tr>
                    <th>Promotion ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($promotions as $promotion): ?>
                <tr>
                    <td><?php echo $promotion['id']; ?></td>
                    <td><?php echo $promotion['title']; ?></td>
                    <td><?php echo $promotion['description']; ?></td>
                    <td><?php echo $promotion['start_date']; ?></td>
                    <td><?php echo $promotion['end_date']; ?></td>
                    <td>
                        <a href="edit_promotion.php?id=<?php echo $promotion['id']; ?>">Edit</a>
                        <a href="delete_promotion.php?id=<?php echo $promotion['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FRESHMART POS. All rights reserved.</p>
    </footer>
</body>
</html>
