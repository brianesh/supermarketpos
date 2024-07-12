<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Example: Get order details from database (replace with actual query)
$order_id = $_GET['id'] ?? null;
$order = ['id' => $order_id, 'supplier' => 'Supplier X', 'total_amount' => 800.00, 'order_date' => '2024-06-18']; // Example data

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission (update in database, etc.)

    // Redirect to purchase order list after updating order
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Purchase Order - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Edit Purchase Order</h1>
        <nav>
            <ul>
                <li><a href="index.php">Purchase Orders List</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Edit Purchase Order Details</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="supplier">Supplier:</label>
                <select id="supplier" name="supplier" required>
                    <option value="Supplier A" <?php echo ($order['supplier'] === 'Supplier A') ? 'selected' : ''; ?>>Supplier A</option>
                    <option value="Supplier B" <?php echo ($order['supplier'] === 'Supplier B') ? 'selected' : ''; ?>>Supplier B</option>
                    <option value="Supplier C" <?php echo ($order['supplier'] === 'Supplier C') ? 'selected' : ''; ?>>Supplier C</option>
                </select><br><br>

                <label for="total_amount">Total Amount:</label>
                <input type="text" id="total_amount" name="total_amount" value="<?php echo htmlspecialchars($order['total_amount']); ?>" required><br><br>

                <label for="order_date">Order Date:</label>
                <input type="date" id="order_date" name="order_date" value="<?php echo $order['order_date']; ?>" required><br><br>

                <button type="submit">Update Purchase Order</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FRESHMART POS. All rights reserved.</p>
    </footer>
</body>
</html>
