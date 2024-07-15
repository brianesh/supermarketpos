<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "supermarketpos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'cashier') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Fetch company name from session
$company_name = isset($_SESSION['company_name']) ? $_SESSION['company_name'] : '';

// SQL query to fetch products
$sql = "SELECT product_id, name, category_name, price, cost, sku, barcode, quantity, description, created_at, updated_at, expiration_date FROM products";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    // Fetching products into an array
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - <?php echo htmlspecialchars($company_name); ?> POS</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header>
        <h1>Manage Products</h1>
        <nav>
            <ul>
                <li><a href="index.php">Cashier Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Product List</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category Name</th>
                    <th>Price</th>
                    <th>Cost</th>
                    <th>SKU</th>
                    <th>Barcode</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Expiration Date</th>
                </tr>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['product_id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['category_name']; ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td>$<?php echo number_format($product['cost'], 2); ?></td>
                    <td><?php echo $product['sku']; ?></td>
                    <td><?php echo $product['barcode']; ?></td>
                    <td><?php echo $product['quantity']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo $product['created_at']; ?></td>
                    <td><?php echo $product['updated_at']; ?></td>
                    <td><?php echo $product['expiration_date']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($company_name); ?> POS. All rights reserved.</p>
    </footer>
</body>
</html>
