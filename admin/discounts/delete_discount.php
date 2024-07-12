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

// Get the discount ID from the query string
$id = $_GET['id'] ?? '';

if ($id) {
    // Prepare and execute the delete statement
    $sql = "DELETE FROM discounts WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Discount deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete discount.';
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Invalid discount ID.';
}

// Close database connection
$mysqli->close();

// Redirect to discounts index
header('Location: index.php');
exit;
