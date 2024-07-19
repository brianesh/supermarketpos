<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../includes/db.php');
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'cashier' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../index.php');
    exit;
}

$query = "SELECT product_id, name, price FROM products";
$result = $mysqli->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Sale - FRESHMART POS</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-image">
            <h1><img src="../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
        </div>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="category.php">Category</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="pos.php">POS</a></li>
            <li><a href="expiredgoods.php">Expired Goods</a></li>
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
        <section>
            <h2>POS Sale</h2>
            <div class="search-section">
                <label for="product-search">Search Product:</label>
                <input type="text" id="product-search" placeholder="Type product name">
                <button id="add-product">Add Product</button>
            </div>
            <div class="payment-section">
                <label for="payment-method">Payment Method:</label>
                <select id="payment-method">
                    <option value="cash">Cash</option>
                    <option value="credit">Credit</option>
                    <option value="debit">Debit</option>
                </select>
            </div>
            <table id="sale-table">
                <thead>
                    <tr>
                        <th>Item Number</th>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Grand Total:</td>
                        <td colspan="2" id="grand-total">$0.00</td>
                    </tr>
                </tfoot>
            </table>
            <button id="make-sale">Make Sale</button>
        </section>
    </div>

</body>
</html>
