<?php
session_start();
$mysqli = include('../includes/db.php');
require_once('../includes/functions.php');

// Validate user role and session
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'cashier' && $_SESSION['role'] !== 'admin')) {
    error_log('Unauthorized access');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if POST data is received
if (!isset($_POST['paymentMethod']) || !isset($_POST['saleItems'])) {
    error_log('Invalid request: Missing paymentMethod or saleItems');
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$paymentMethod = $_POST['paymentMethod'];
$saleItems = $_POST['saleItems'];

// Validate saleItems
if (!is_array($saleItems) || count($saleItems) === 0) {
    error_log('Invalid sale items');
    echo json_encode(['success' => false, 'message' => 'Invalid sale items']);
    exit;
}

// Calculate total amount of the sale
$totalAmount = 0;
foreach ($saleItems as $item) {
    if (!isset($item['productName']) || !isset($item['unitPrice']) || !isset($item['quantity']) || !isset($item['subtotal'])) {
        error_log('Invalid sale item format: ' . print_r($item, true));
        echo json_encode(['success' => false, 'message' => 'Invalid sale item format']);
        exit;
    }
    $totalAmount += $item['subtotal'];
}

// Log sale details for debugging
error_log("Processing sale: paymentMethod = $paymentMethod, totalAmount = $totalAmount, saleItems = " . print_r($saleItems, true));

// Insert sale into database
$insertSaleQuery = "INSERT INTO sales (total_amount, payment_method, created_at) VALUES ($totalAmount, '$paymentMethod', NOW())";
if ($mysqli->query($insertSaleQuery)) {
    $saleId = $mysqli->insert_id;

    // Insert sale items into database
    foreach ($saleItems as $item) {
        $productName = $mysqli->real_escape_string($item['productName']);
        $unitPrice = $item['unitPrice'];
        $quantity = $item['quantity'];
        $subtotal = $item['subtotal'];

        $insertSaleItemQuery = "INSERT INTO sale_items (sale_id, product_name, unit_price, quantity, subtotal) VALUES ($saleId, '$productName', $unitPrice, $quantity, $subtotal)";
        if (!$mysqli->query($insertSaleItemQuery)) {
            error_log('Failed to insert sale item: ' . $mysqli->error);
            echo json_encode(['success' => false, 'message' => 'Failed to insert sale item']);
            exit;
        }
    }

    $response = ['success' => true, 'sale_id' => $saleId];
    if ($paymentMethod === 'cash' && isset($_POST['cashReceived'])) {
        $cashReceived = $_POST['cashReceived'];
        $balance = $cashReceived - $totalAmount;
        $response['balance'] = $balance;
    }
    echo json_encode($response);
} else {
    error_log('Failed to process the sale: ' . $mysqli->error);
    echo json_encode(['success' => false, 'message' => 'Failed to process the sale']);
}

$mysqli->close();
?>
