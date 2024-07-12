<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Include database connection
require_once '../../includes/db.php';

// Initialize variables for form input
$id = $_GET['id'] ?? '';
$title = $product_id = $percentage = $start_date = $end_date = '';
$errors = [];

// Fetch products for dropdown (example)
$sql = "SELECT id, name FROM products ORDER BY name";
$product_result = $mysqli->query($sql);

if (!$product_result) {
    $_SESSION['error'] = 'Failed to fetch products.';
    header('Location: index.php'); // Redirect to discounts index or handle error
    exit;
}

$products = $product_result->fetch_all(MYSQLI_ASSOC);
$product_result->close();

// Fetch discount details based on ID
$sql = "SELECT * FROM discounts WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $discount = $result->fetch_assoc();
    $title = $discount['title'];
    $product_id = $discount['product_id'];
    $percentage = $discount['percentage'];
    $start_date = $discount['start_date'];
    $end_date = $discount['end_date'];
} else {
    $_SESSION['error'] = 'Discount not found.';
    header('Location: index.php'); // Redirect to discounts index if discount not found
    exit;
}

$stmt->close();

// Process form submission
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

    // If no errors, update in database
    if (empty($errors)) {
        $sql = "UPDATE discounts SET title = ?, product_id = ?, percentage = ?, start_date = ?, end_date = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('siissi', $title, $product_id, $percentage, $start_date, $end_date, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Discount updated successfully.';
            $stmt->close();
            $mysqli->close();
            header('Location: index.php'); // Redirect to discounts index after updating
            exit;
        } else {
            $_SESSION['error'] = 'Failed to update discount.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Discount - Supermarket POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Edit Discount</h1>
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
            <h2>Edit Discount Details</h2>
            <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>">
                <label for="title">Title:</label><br>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br><br>
                
                <label for="product_id">Product:</label><br>
                <select id="product_id" name="product_id">
                    <option value="">Select Product</option>
                    <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>" <?php if ($product['id'] == $product_id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($product['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select><br><br>
                
                <label for="percentage">Percentage:</label><br>
                <input type="number" id="percentage" name="percentage" min="1" max="100" value="<?php echo htmlspecialchars($percentage); ?>"><br><br>
                
                <label for="start_date">Start Date:</label><br>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>"><br><br>
                
                <label for="end_date">End Date:</label><br>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>"><br><br>
                
                <input type="submit" value="Update Discount">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Supermarket POS. All rights reserved.</p>
    </footer>
</body>
</html>
