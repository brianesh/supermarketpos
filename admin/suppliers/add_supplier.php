<?php
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    
    header('Location: ../../index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Supplier - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Add New Supplier</h1>
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
            <h2>Supplier Details</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required><br><br>

                <button type="submit">Add Supplier</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FRESHMART POS. All rights reserved.</p>
    </footer>
</body>
</html>
