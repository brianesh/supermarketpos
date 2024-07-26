<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = include('../../includes/db.php');

// Validate session and user role
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'cashier' && $_SESSION['role'] !== 'admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Handle POST data from AJAX request
$paymentMethod = $_POST['method_name'] ?? '';
$saleItems = $_POST['sale_details'] ?? [];
$cashReceived = isset($_POST['cashReceived']) ? floatval($_POST['cashReceived']) : 0;

// Validate sale items
if (empty($saleItems)) {
    echo json_encode(['success' => false, 'message' => 'No sale items provided']);
    exit;
}

// Calculate total amount of the sale
$totalAmount = 0;
foreach ($saleItems as $item) {
    $totalAmount += $item['subtotal'];
}

// Insert sale into sales table
$query = "INSERT INTO sales (user_id, total_amount, method_name, sale_date, created_at) VALUES (?, ?, ?, NOW(), NOW())";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param('iis', $_SESSION['user_id'], $totalAmount, $paymentMethod);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
    exit;
}
$saleId = $stmt->insert_id; // Get the ID of the newly inserted sale
$stmt->close();

// Insert sale items into sale_details table
$query = "INSERT INTO sale_details (sale_id, product_id, quantity, price, discount, total) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

// Fetch product IDs
$productIds = [];
foreach ($saleItems as $item) {
    $stmt = $mysqli->prepare("SELECT product_id FROM products WHERE name = ?");
    $stmt->bind_param('s', $item['productName']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $productIds[$item['productName']] = $product['product_id'];
    $stmt->close();
}

// Insert each item into sale_details
foreach ($saleItems as $item) {
    $productId = $productIds[$item['productName']] ?? null;
    if ($productId) {
        $stmt->bind_param('iiiddi', $saleId, $productId, $item['quantity'], $item['unitPrice'], 0, $item['subtotal']);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
            exit;
        }
    }
}
$stmt->close();

// Handle payment-specific logic
$response = ['success' => true, 'sale_id' => $saleId];
if ($paymentMethod === 'cash') {
    if ($cashReceived < $totalAmount) {
        echo json_encode(['success' => false, 'message' => 'Insufficient cash received']);
        exit;
    }
    $balance = $cashReceived - $totalAmount;
    $response['balance'] = $balance;
}

// Close database connection
$mysqli->close();

// Output JSON response
echo json_encode($response);
?>
