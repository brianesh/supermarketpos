<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Mock purchase orders data (replace with actual database query)
$purchase_orders = [
    ['id' => 1, 'supplier_id' => 1, 'order_date' => '2024-06-01', 'total_amount' => 500.00],
    ['id' => 2, 'supplier_id' => 2, 'order_date' => '2024-06-02', 'total_amount' => 800.00],
    ['id' => 3, 'supplier_id' => 3, 'order_date' => '2024-06-03', 'total_amount' => 1200.00],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Purchase Orders - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Manage Purchase Orders</h1>
        <nav>
            <ul>
                <li><a href="add_order.php">Add New Order</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Purchase Order List</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Supplier ID</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($purchase_orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['supplier_id']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td>
                        <a href="edit_order.php?id=<?php echo $order['id']; ?>">Edit</a>
                        <a href="delete_order.php?id=<?php echo $order['id']; ?>">Delete</a>
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
