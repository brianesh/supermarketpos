<?php
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
   
    header('Location: ../../index.php');
    exit;
}


$suppliers = [
    ['id' => 1, 'name' => 'Supplier A', 'email' => 'supplierA@example.com', 'phone' => '123-456-7890'],
    ['id' => 2, 'name' => 'Supplier B', 'email' => 'supplierB@example.com', 'phone' => '987-654-3210'],
    ['id' => 3, 'name' => 'Supplier C', 'email' => 'supplierC@example.com', 'phone' => '555-555-5555'],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Suppliers - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Manage Suppliers</h1>
        <nav>
            <ul>
                <li><a href="add_supplier.php">Add New Supplier</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Supplier List</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($suppliers as $supplier): ?>
                <tr>
                    <td><?php echo $supplier['id']; ?></td>
                    <td><?php echo $supplier['name']; ?></td>
                    <td><?php echo $supplier['email']; ?></td>
                    <td><?php echo $supplier['phone']; ?></td>
                    <td>
                        <a href="edit_supplier.php?id=<?php echo $supplier['id']; ?>">Edit</a>
                        <a href="delete_supplier.php?id=<?php echo $supplier['id']; ?>">Delete</a>
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
