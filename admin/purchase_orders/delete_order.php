<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Include database connection
require_once '../../includes/db.php';

// Check if order ID is provided and valid
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Sanitize the ID
    $order_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare DELETE statement
    $sql = "DELETE FROM purchase_orders WHERE id = ?";

    // Prepare and bind parameters
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $order_id);

    // Attempt to execute the statement
    if ($stmt->execute()) {
        // Redirect to purchase order list after successful deletion
        $stmt->close(); // Close statement
        $mysqli->close(); // Close connection
        header('Location: index.php');
        exit;
    } else {
        // Error handling: Redirect with error message
        $_SESSION['error'] = 'Failed to delete purchase order.';
        $stmt->close(); // Close statement
        $mysqli->close(); // Close connection
        header('Location: index.php');
        exit;
    }
} else {
    // Redirect to purchase order list if no ID is provided
    header('Location: index.php');
    exit;
}
?>
