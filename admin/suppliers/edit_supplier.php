<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Example: Get supplier details from database (replace with actual query)
$supplier_id = $_GET['id'] ?? null;
$supplier = ['id' => $supplier_id, 'name' => 'Supplier X', 'email' => 'supplierX@example.com', 'phone' => '111-222-3333']; // Example data

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission (update in database, etc.)

    // Redirect to supplier list after updating supplier
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Supplier - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Edit Supplier</h1>
        <nav>
            <ul>
                <li><a href="index.php">Supplier List</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Edit Supplier Details</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($supplier['name']); ?>" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($supplier['email']); ?>" required><br><br>

                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($supplier['phone']); ?>" required><br><br>

                <button type="submit">Update Supplier</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FRESHMART POS. All rights reserved.</p>
    </footer>
</body>
</html>
