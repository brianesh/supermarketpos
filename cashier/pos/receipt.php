<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$query = "SELECT * FROM sales WHERE sale_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $saleId); // Assuming sale_id is an integer type (adjust if it's not)
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
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h2>FRESHMART SUPERMARKET</h2>
            <p>Date: <?php echo date('Y-m-d H:i:s', strtotime($sale['created_at'])); ?></p>
        <h3>00232 RUIRU</h3>
        <h3>Tel: 0758489080</h3>
        <h3>freshmart@gmail.com</h3>
        </div>
        <div class="receipt-items">
            <center><h2>SALE RECEIPT</h2><center>
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
        <h4></h4>
            <h4>Paid Via: <?php echo htmlspecialchars($method_name); ?></h4>
    </div>
</body>
</html>
