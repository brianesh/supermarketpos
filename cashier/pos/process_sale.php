<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection and functions
$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

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
$stmt->bind_param('sss', $_SESSION['user_id'], $totalAmount, $paymentMethod);
$stmt->execute();
$saleId = $stmt->insert_id; // Get the ID of the newly inserted sale
$stmt->close();

// Insert sale items into sale_details table
$query = "INSERT INTO sale_details (sale_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($query);
foreach ($saleItems as $item) {
    $stmt->bind_param('issdd', $saleId, $item['productName'], $item['unitPrice'], $item['quantity'], $item['subtotal']);
    $stmt->execute();
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
