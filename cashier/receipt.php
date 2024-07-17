<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../includes/db.php');
require_once('../includes/functions.php');

if (!isset($_GET['sale_id'])) {
    header('Location: pos.php');
    exit;
}

$saleId = $_GET['sale_id'];

// Fetch sale details from the database
$query = "SELECT * FROM sales WHERE sale_id = $saleId";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    $sale = $result->fetch_assoc();
} else {
    echo "Sale not found.";
    exit;
}

// Fetch sale items from the database
$queryItems = "SELECT * FROM sale_items WHERE sale_id = $saleId";
$resultItems = $mysqli->query($queryItems);
$items = [];
while ($row = $resultItems->fetch_assoc()) {
    $items[] = $row;
}

$mysqli->close();
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
            <h2>Receipt - FRESHMART POS</h2>
            <p>Date: <?php echo date('Y-m-d H:i:s', strtotime($sale['created_at'])); ?></p>
        </div>
        <div class="receipt-items">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo $item['product_name']; ?></td>
                            <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="receipt-total">
            <p>Grand Total: $<?php echo number_format($sale['total_amount'], 2); ?></p>
        </div>
    </div>
</body>
</html>
