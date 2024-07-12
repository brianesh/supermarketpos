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

// Fetch sales records from database (example)
$sql = "SELECT * FROM sales_records ORDER BY sale_date DESC";
$result = $mysqli->query($sql);

// Check if query was successful
if (!$result) {
    $_SESSION['error'] = 'Failed to fetch sales records.';
    header('Location: index.php'); // Redirect to reports index or handle error
    exit;
}

// Fetch all rows into an associative array
$sales_records = $result->fetch_all(MYSQLI_ASSOC);

// Close result set and database connection
$result->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Sales Report</h1>
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
            <h2>Sales Records</h2>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Sale Date</th>
                </tr>
                <?php foreach ($sales_records as $record): ?>
                <tr>
                    <td><?php echo $record['order_id']; ?></td>
                    <td><?php echo $record['customer_name']; ?></td>
                    <td>$<?php echo number_format($record['total_amount'], 2); ?></td>
                    <td><?php echo $record['sale_date']; ?></td>
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
