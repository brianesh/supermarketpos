<?php
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    
    header('Location: ../../index.php');
    exit;
}


require_once '../../includes/db.php';

$sql = "SELECT * FROM purchase_orders ORDER BY order_date DESC";
$result = $mysqli->query($sql);


if (!$result) {
    $_SESSION['error'] = 'Failed to fetch procurement data.';
    header('Location: index.php'); // Redirect to reports index or handle error
    exit;
}

// Fetch all rows into an associative array
$procurement_orders = $result->fetch_all(MYSQLI_ASSOC);

// Close result set and database connection
$result->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Procurement Report - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Procurement Report</h1>
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
            <h2>Procurement Orders</h2>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Supplier</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                </tr>
                <?php foreach ($procurement_orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['supplier']; ?></td>
                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo $order['order_date']; ?></td>
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
