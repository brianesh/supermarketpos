<?php
session_start();
$mysqli = include('../includes/db.php');
require_once('../includes/functions.php');

// Validate user role and session
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'cashier' && $_SESSION['role'] !== 'admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if POST data is received
if (!isset($_POST['paymentMethod']) || !isset($_POST['saleItems'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$paymentMethod = $_POST['paymentMethod'];
$saleItems = $_POST['saleItems'];

// Calculate total amount of the sale
$totalAmount = 0;
foreach ($saleItems as $item) {
    $totalAmount += $item['subtotal'];
}

// Insert sale into database
$insertSaleQuery = "INSERT INTO sales (total_amount, payment_method, created_at) VALUES ($totalAmount, '$paymentMethod', NOW())";
if ($mysqli->query($insertSaleQuery)) {
    $saleId = $mysqli->insert_id;

    // Insert sale items into database
    foreach ($saleItems as $item) {
        $productName = $item['productName'];
        $unitPrice = $item['unitPrice'];
        $quantity = $item['quantity'];
        $subtotal = $item['subtotal'];

        $insertItemQuery = "INSERT INTO sale_items (sale_id, product_name, unit_price, quantity, subtotal) VALUES ($saleId, '$productName', $unitPrice, $quantity, $subtotal)";
        $mysqli->query($insertItemQuery);
    }

    // Handle cash payment balance calculation
    if ($paymentMethod === 'cash' && isset($_POST['cashReceived'])) {
        $cashReceived = $_POST['cashReceived'];
        $balance = $cashReceived - $totalAmount;
        echo json_encode(['success' => true, 'sale_id' => $saleId, 'balance' => $balance]);
    } else {
        echo json_encode(['success' => true, 'sale_id' => $saleId]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error creating sale']);
}

$mysqli->close();
?>
