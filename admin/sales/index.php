<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../includes/db.php';

// Fetch products from the database
$products_query = "SELECT product_id, name, price FROM products";
$products_result = $mysqli->query($products_query);

// Handle form submission for completing the sale
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_sale'])) {
    $user_id = 1; // Assume a logged-in user ID, this should be dynamically set
    $customer_id = $_POST['customer_id'];
    $sale_date = date('Y-m-d H:i:s');
    $total_amount = $_POST['total_amount'];
    $payment_method_id = $_POST['payment_method_id'];

    // Insert new sale into the database
    $insert_sale_query = "INSERT INTO sales (user_id, customer_id, sale_date, total_amount, payment_method_id) 
                          VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insert_sale_query);
    $stmt->bind_param("iisds", $user_id, $customer_id, $sale_date, $total_amount, $payment_method_id);
    $stmt->execute();
    $sale_id = $stmt->insert_id;
    $stmt->close();

    // Insert each sale item into the database
    foreach ($_POST['products'] as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];
        $price = $product['price'];
        $insert_sale_item_query = "INSERT INTO sale_items (sale_id, product_id, quantity, price) 
                                   VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insert_sale_item_query);
        $stmt->bind_param("iiid", $sale_id, $product_id, $quantity, $price);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to receipt page
    header("Location: receipt.php?sale_id=$sale_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Sales Module</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file here -->
</head>
<body>
    <h1>Sales Module</h1>
    <form action="index.php" method="post">
        <label for="customer_id">Customer ID:</label>
        <input type="text" id="customer_id" name="customer_id" required><br>

        <label for="payment_method_id">Payment Method ID:</label>
        <input type="text" id="payment_method_id" name="payment_method_id" required><br>

        <h2>Select Products</h2>
        <div id="products">
            <?php while ($product = $products_result->fetch_assoc()): ?>
                <div>
                    <input type="checkbox" name="products[<?php echo $product['product_id']; ?>][product_id]" value="<?php echo $product['product_id']; ?>">
                    <label><?php echo $product['name']; ?> - $<?php echo $product['price']; ?></label>
                    <input type="hidden" name="products[<?php echo $product['product_id']; ?>][price]" value="<?php echo $product['price']; ?>">
                    <input type="number" name="products[<?php echo $product['product_id']; ?>][quantity]" value="1" min="1">
                </div>
            <?php endwhile; ?>
        </div>

        <input type="hidden" id="total_amount" name="total_amount">
        <input type="submit" name="complete_sale" value="Complete Sale">
    </form>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            let totalAmount = 0;
            document.querySelectorAll('#products input[type="checkbox"]').forEach(function(checkbox) {
                if (checkbox.checked) {
                    const productElement = checkbox.closest('div');
                    const price = parseFloat(productElement.querySelector('input[type="hidden"]').value);
                    const quantity = parseInt(productElement.querySelector('input[type="number"]').value);
                    totalAmount += price * quantity;
                }
            });
            document.getElementById('total_amount').value = totalAmount.toFixed(2);
        });
    </script>
</body>
</html>

<?php
// Close the database connection
$mysqli->close();
?>
