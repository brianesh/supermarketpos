<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Check if promotion ID is provided via GET
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'Promotion ID not specified.';
    header('Location: index.php'); // Redirect to promotions index if ID is missing
    exit;
}

$promotion_id = $_GET['id'];

// Include database connection
require_once '../../includes/db.php';

// Delete promotion from database
$sql = "DELETE FROM promotions WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $promotion_id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Promotion deleted successfully.';
} else {
    $_SESSION['error'] = 'Failed to delete promotion.';
}

$stmt->close();
$mysqli->close();

// Redirect to promotions index after deletion
header('Location: index.php');
exit;
?>
