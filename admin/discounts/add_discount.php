<?php
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    
    header('Location: ../../index.php');
    exit;
}


require_once '../../includes/db.php';


$title = $product_id = $percentage = $start_date = $end_date = '';
$errors = [];

$sql = "SELECT id, name FROM products ORDER BY name";
$product_result = $mysqli->query($sql);

if (!$product_result) {
    $_SESSION['error'] = 'Failed to fetch products.';
    header('Location: index.php');
    exit;
}

$products = $product_result->fetch_all(MYSQLI_ASSOC);
$product_result->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $title = trim($_POST['title']);
    $product_id = $_POST['product_id'];
    $percentage = trim($_POST['percentage']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Basic validation
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    if (empty($product_id)) {
        $errors[] = 'Product is required.';
    }
    if (empty($percentage) || !is_numeric($percentage) || $percentage <= 0 || $percentage > 100) {
        $errors[] = 'Percentage must be a number between 0 and 100.';
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO discounts (title, product_id, percentage, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('siiss', $title, $product_id, $percentage, $start_date, $end_date);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Discount added successfully.';
            $stmt->close();
            $mysqli->close();
            header('Location: index.php'); // Redirect to discounts index after adding
            exit;
        } else {
            $_SESSION['error'] = 'Failed to add discount.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Discount - Supermarket POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Add New Discount</h1>
        <nav>
            <ul>
                <li><a href="index.php">Back to Discounts</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Enter Discount Details</h2>
            <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="title">Title:</label><br>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br><br>
                
                <label for="product_id">Product:</label><br>
                <select id="product_id" name="product_id">
                    <option value="">Select Product</option>
                    <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                    <?php endforeach; ?>
                </select><br><br>
                
                <label for="percentage">Percentage:</label><br>
                <input type="number" id="percentage" name="percentage" min="1" max="100" value="<?php echo htmlspecialchars($percentage); ?>"><br><br>
                
                <label for="start_date">Start Date:</label><br>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>"><br><br>
                
                <label for="end_date">End Date:</label><br>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>"><br><br>
                
                <input type="submit" value="Add Discount">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Supermarket POS. All rights reserved.</p>
    </footer>
</body>
</html>
