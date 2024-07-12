<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Example: Handle deletion logic (replace with actual deletion code)
$supplier_id = $_GET['id'] ?? null;
if ($supplier_id) {
    // Process deletion (delete from database, etc.)

    // Redirect to supplier list after deletion
    header('Location: index.php');
    exit;
}
?>
