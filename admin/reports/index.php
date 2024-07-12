<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Reports</h1>
        <nav>
            <ul>
                <li><a href="sales_report.php">Sales Report</a></li>
                <li><a href="inventory_report.php">Inventory Report</a></li>
                <li><a href="procurement_report.php">Procurement Report</a></li>
                <li><a href="user_report.php">User Report</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Available Reports</h2>
            <ul>
                <li><a href="sales_report.php">Sales Report</a></li>
                <li><a href="inventory_report.php">Inventory Report</a></li>
                <li><a href="procurement_report.php">Procurement Report</a></li>
                <li><a href="user_report.php">User Report</a></li>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FRESHMART POS. All rights reserved.</p>
    </footer>
</body>
</html>
