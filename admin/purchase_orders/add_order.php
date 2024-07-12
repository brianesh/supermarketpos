<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Example: Get suppliers list from database (replace with actual query)
$suppliers = [
    ['id' => 1, 'name' => 'Supplier A'],
    ['id' => 2, 'name' => 'Supplier B'],
    ['id' => 3, 'name' => 'Supplier C'],
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission (insert into database, etc.)

    // Redirect to purchase order list after adding order
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Purchase Order - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Add New Purchase Order</h1>
        <nav>
            <ul>
                <li><a href="index.php">Purchase Order List</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Order Details</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="supplier_id">Supplier:</label>
                <select id="supplier_id" name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <label for="order_date">Order Date:</label>
                <input type="date" id="order_date" name="order_date" required><br><br>

                <label for="total_amount">Total Amount:</label>
                <input type="text" id="total_amount" name="total_amount" required><br><br>

                <button type="submit">Add Order</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FRESHMART POS. All rights reserved.</p>
    </footer>
</body>
</html>
