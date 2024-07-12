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

// Fetch users data from database
$sql = "SELECT * FROM users ORDER BY username";
$result = $mysqli->query($sql);

// Check if query was successful
if (!$result) {
    $_SESSION['error'] = 'Failed to fetch users data.';
    header('Location: index.php'); // Redirect to reports index or handle error
    exit;
}

// Fetch all rows into an associative array
$users = $result->fetch_all(MYSQLI_ASSOC);

// Close result set and database connection
$result->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Report - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>User Report</h1>
        <nav>
            <ul>
                <li><a href="index.php">Back to Reports</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>User List</h2>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo ucfirst($user['role']); ?></td>
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
