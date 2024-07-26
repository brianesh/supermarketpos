<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../includes/db.php'); // Ensure this returns $mysqli

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
    if (!isset($item['subtotal'])) {
        echo json_encode(['success' => false, 'message' => 'Subtotal missing for an item']);
        exit;
    }
    $totalAmount += floatval($item['subtotal']); // Ensure subtotal is a float
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
$query = "INSERT INTO sale_details (sale_id, productName, quantity, price, discount, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

foreach ($saleItems as $item) {
    if (!isset($item['productName'], $item['quantity'], $item['unitPrice'], $item['subtotal'])) {
        echo json_encode(['success' => false, 'message' => 'Missing data for an item']);
        exit;
    }

    $discount = isset($item['discount']) ? floatval($item['discount']) : 0;

    $stmt->bind_param('isiddi', $saleId, $item['productName'], $item['quantity'], $item['unitPrice'], $discount, $item['subtotal']);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
        exit;
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
