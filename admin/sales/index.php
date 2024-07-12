<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Mock sales data (replace with actual database query)
$sales = [
    ['id' => 1, 'date' => '2024-06-01', 'total_amount' => 150.00],
    ['id' => 2, 'date' => '2024-06-02', 'total_amount' => 250.00],
    ['id' => 3, 'date' => '2024-06-03', 'total_amount' => 180.00],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Management - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Sales Management</h1>
        <nav>
            <ul>
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
                    <th>ID</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?php echo $sale['id']; ?></td>
                    <td><?php echo $sale['date']; ?></td>
                    <td>$<?php echo number_format($sale['total_amount'], 2); ?></td>
                    <td>
                        <a href="view_sale.php?id=<?php echo $sale['id']; ?>">View</a>
                        <a href="edit_sale.php?id=<?php echo $sale['id']; ?>">Edit</a>
                        <a href="delete_sale.php?id=<?php echo $sale['id']; ?>">Delete</a>
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
