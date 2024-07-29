<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Include Composer autoload
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Include database connection and functions
$mysqli = include('../../includes/db.php'); // Assuming db.php returns $mysqli
require_once('../../includes/functions.php');

// Redirect if sale_id is not provided or not valid
if (!isset($_GET['sale_id']) || !is_numeric($_GET['sale_id'])) {
    header('Location: index.php'); // Adjust this redirection to your actual POS page
    exit;
}

$saleId = $_GET['sale_id'];

// Fetch sale details from the database using prepared statement
$query = "
    SELECT s.*, u.full_name
    FROM sales s
    JOIN users u ON s.user_id = u.user_id
    WHERE s.sale_id = ?
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $saleId); // Assuming sale_id is an integer type
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sale = $result->fetch_assoc();

    // Fetch sale items from the database using prepared statement
    $queryItems = "SELECT * FROM sale_details WHERE sale_id = ?";
    $stmtItems = $mysqli->prepare($queryItems);
    $stmtItems->bind_param('i', $saleId); // Assuming sale_id is an integer type
    $stmtItems->execute();
    $resultItems = $stmtItems->get_result();

    $items = [];
    while ($row = $resultItems->fetch_assoc()) {
        $items[] = $row;
    }

    $stmtItems->close(); // Close the statement after fetching items
} else {
    echo "Sale not found."; // Handle case where sale_id doesn't match any record
    exit;
}

$method_name = $sale['method_name'];
$served_by = $sale['full_name'] ?? 'Unknown'; // Provide a default value if full_name is null

// Collect additional information for the QR code
$qrCodeData = "FRESHMART SUPERMARKET\n";
$qrCodeData .= "Date: " . date('Y-m-d H:i:s', strtotime($sale['created_at'])) . "\n";
$qrCodeData .= "Receipt Number: " . $saleId . "\n";
$qrCodeData .= "Paid Via: " . htmlspecialchars($method_name) . "\n";
$qrCodeData .= "Served By: " . htmlspecialchars($served_by) . "\n";
$qrCodeData .= "Items:\n";

// Add item details to the QR code
foreach ($items as $item) {
    $qrCodeData .= htmlspecialchars($item['productName']) . " - ";
    $qrCodeData .= "Ksh" . number_format($item['price'], 2) . " x ";
    $qrCodeData .= $item['quantity'] . " = ";
    $qrCodeData .= "Ksh" . number_format($item['subtotal'], 2) . "\n";
}

$qrCodeData .= "Grand Total: Ksh" . number_format($sale['total_amount'], 2);

$qrCode = new QrCode($qrCodeData);
$qrCode->setSize(150);
$qrCode->setMargin(10);

// Directory to save QR code
$directory = __DIR__ . '/qrcodes/';

// Check if directory exists
if (!is_dir($directory)) {
    // Try to create the directory
    if (!mkdir($directory, 0777, true)) {
        die('Failed to create directory: ' . $directory);
    }
}

// Use PngWriter to save the QR code as a PNG file
$writer = new PngWriter();
$qrCodeFilePath = $directory . 'receipt_' . $saleId . '.png';
$result = $writer->write($qrCode);
file_put_contents($qrCodeFilePath, $result->getString()); // Save the QR code to a file

$stmt->close(); // Close the statement after fetching sale details
$mysqli->close(); // Close the database connection

// Display the receipt
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sale Receipt - FRESHMART POS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .receipt-container {
            width: 300px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .receipt-header {
            margin-bottom: 10px;
        }
        .receipt-items {
            text-align: left;
            margin-bottom: 20px;
        }
        .receipt-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt-items th, .receipt-items td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .receipt-total {
            margin-top: 20px;
            font-weight: bold;
        }
        .qr-code {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h2>FRESHMART SUPERMARKET</h2>
            <?php date_default_timezone_set('Africa/Nairobi');?>
            <p>Date: <?php echo date('Y-m-d H:i:s', strtotime($sale['created_at'])); ?></p>
            <h3>00232 RUIRU</h3>
            <h3>Tel: 0758489080</h3>
            <h3>freshmart@gmail.com</h3>
        </div>
        <div class="receipt-items">
            <center><h2>SALE RECEIPT</h2></center>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['productName']); ?></td>
                            <td>Ksh<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Ksh<?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="receipt-total">
            <p>Grand Total: Ksh<?php echo number_format($sale['total_amount'], 2); ?></p>
        </div>
        <h4>Paid Via: <?php echo htmlspecialchars($method_name); ?></h4>
        <h4>Served By: <?php echo htmlspecialchars($served_by); ?></h4>
        <div class="qr-code">
            <img src="qrcodes/receipt_<?php echo $saleId; ?>.png" alt="QR Code" />
        </div>
    </div>
</body>
</html>
