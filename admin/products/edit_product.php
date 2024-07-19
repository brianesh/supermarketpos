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


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    
    header('Location: ../../index.php');
    exit;
}


$company_name = isset($_SESSION['company_name']) ? $_SESSION['company_name'] : 'Your Company Name';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $category_name = $_POST['category_name'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $sku = $_POST['sku'];
    $barcode = $_POST['barcode'];
    $barcode = $_POST['quantity'];
    $description = $_POST['description'];
    $expiration_date = $_POST['expiration_date'];
    
  
    $sql = "UPDATE products SET 
            name=?, category_name=?, price=?, cost=?, 
            sku=?, barcode=?, quantity=?, description=?, expiration_date=? 
            WHERE product_id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $name, $category_name, $price, $cost, $sku, $barcode, $quantity, $description, $expiration_date, $product_id);
    
    if ($stmt->execute()) {
       
        header('Location: index.php');
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}


if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE product_id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found";
        exit;
    }
} else {
    echo "Product ID not provided";
    exit;
}

$conn->close(); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - <?php echo htmlspecialchars($company_name); ?> POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Edit Product</h1>
        <nav>
            <ul>
                <li><a href="index.php">Product List</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Edit Product Details</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required><br><br>

                <label for="category_id">Category Name:</label>
                <input type="text" id="category_name" name="category_name" value="<?php echo $product['category_name']; ?>" required><br><br>

                <label for="price">Price:</label>
                <input type="text" id="price" name="price" value="<?php echo $product['price']; ?>" required><br><br>

                <label for="cost">Cost:</label>
                <input type="text" id="cost" name="cost" value="<?php echo $product['cost']; ?>" required><br><br>

                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" value="<?php echo $product['sku']; ?>" required><br><br>

                <label for="barcode">Barcode:</label>
                <input type="text" id="barcode" name="barcode" value="<?php echo $product['barcode']; ?>" required><br><br>

                <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required><br><br>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo $product['description']; ?></textarea><br><br>

                <label for="expiration_date">Expiration Date:</label>
                <input type="date" id="expiration_date" name="expiration_date" value="<?php echo $product['expiration_date']; ?>" required><br><br>

                <button type="submit" name="edit_product">Save Changes</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($company_name); ?> POS. All rights reserved.</p>
    </footer>
</body>
</html>
