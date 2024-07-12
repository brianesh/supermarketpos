<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Mock inventory data (replace with actual database query)
$inventory = [
    ['id' => 1, 'name' => 'Item A', 'quantity' => 50, 'price' => 5.99],
    ['id' => 2, 'name' => 'Item B', 'quantity' => 30, 'price' => 9.99],
    ['id' => 3, 'name' => 'Item C', 'quantity' => 20, 'price' => 12.99],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management - Supermarket POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Inventory Management</h1>
        <nav>
            <ul>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Current Inventory</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($inventory as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <a href="view_item.php?id=<?php echo $item['id']; ?>">View</a>
                        <a href="edit_item.php?id=<?php echo $item['id']; ?>">Edit</a>
                        <a href="delete_item.php?id=<?php echo $item['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Supermarket POS. All rights reserved.</p>
    </footer>
</body>
</html>
