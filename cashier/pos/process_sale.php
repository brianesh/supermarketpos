<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection or any necessary files
$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

// Validate session and user role (e.g., admin or cashier)
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'cashier' && $_SESSION['role'] !== 'admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Handle POST data from AJAX request
$paymentMethod = $_POST['paymentMethod'] ?? '';
$saleItems = $_POST['saleItems'] ?? [];
$cashReceived = isset($_POST['cashReceived']) ? floatval($_POST['cashReceived']) : 0;

// Example validation: Ensure sale items are provided
if (empty($saleItems)) {
    echo json_encode(['success' => false, 'message' => 'No sale items provided']);
    exit;
}

// Example: Calculate total amount of the sale
$totalAmount = 0;
foreach ($saleItems as $item) {
    $totalAmount += $item['subtotal'];
}

// Example: Handle different payment methods (here we focus on 'cash')
if ($paymentMethod === 'cash') {
    // Validate cash received
    if ($cashReceived < $totalAmount) {
        echo json_encode(['success' => false, 'message' => 'Insufficient cash received']);
        exit;
    }

    // Process sale and calculate balance
    $balance = $cashReceived - $totalAmount;

    // Perform database operations (e.g., update sales records, inventory, etc.)
    // Assuming a successful sale process

    // Generate a sale ID (example)
    $sale_id = uniqid('sale_', true);

    // Prepare JSON response
    $response = [
        'success' => true,
        'balance' => $balance,
        'sale_id' => $sale_id
    ];
} else {
    // Handle other payment methods (not specified in detail here)
    // Assuming direct processing for non-cash methods

    // Perform database operations (e.g., update sales records, inventory, etc.)
    // Assuming a successful sale process

    // Generate a sale ID (example)
    $sale_id = uniqid('sale_', true);

    // Prepare JSON response
    $response = [
        'success' => true,
        'sale_id' => $sale_id
    ];
}

// Close database connection
$mysqli->close();

// Output JSON response
echo json_encode($response);
?>
