<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "supermarketpos";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'cashier') {
   
    header('Location: ../../index.php');
    exit;
}


$company_name = isset($_SESSION['company_name']) ? $_SESSION['company_name'] : '';


$sql = "SELECT product_id, name, category_name, price, cost, sku, barcode, quantity, description, created_at, updated_at, expiration_date FROM products";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - <?php echo htmlspecialchars($company_name); ?> POS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="sidebar">
        <div class="profile-image">
            <h1><img src="../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
        </div>
        <ul>
            <li><a href="">Dashboard</a></li>
            <li><a href="category.php">Category</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="pos/index.php">POS</a></li>
            <li><a href="expired_goods.php">Expired Goods</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="time">
                <?php echo date('l, F j, Y h:i A'); ?>
            </div>
            <div class="notifications">
                <a href="../notifications.php"><i class="fa fa-bell"></i></a>
            </div>
        </div>
    <main>
        <section>
            <form action="process_sale.php" Method="post">
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
            </form>
        </section>
    </main>
</div>
</body>
</html>
