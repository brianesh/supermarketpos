<?php
session_start();
require_once('includes/db.php');

// Handle POST data
$saleId = $_POST['sale_id'] ?? 0;
$receiptData = $_POST['receipt_data'] ?? '';
$printedAt = $_POST['printed_at'] ?? '';

if (empty($saleId) || empty($receiptData) || empty($printedAt)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// Insert receipt into database
$query = "INSERT INTO receipt (sale_id, receipt_data, printed_at) VALUES (?, ?, ?)";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param('iss', $saleId, $receiptData, $printedAt);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
    exit;
}
$receiptId = $stmt->insert_id; // Get the ID of the newly inserted receipt
$stmt->close();

// Close database connection
$mysqli->close();

// Output JSON response
echo json_encode(['success' => true, 'receipt_id' => $receiptId]);
?>
