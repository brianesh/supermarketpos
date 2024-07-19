<?php
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
   
    header('Location: ../../index.php');
    exit;
}


$supplier_id = $_GET['id'] ?? null;
if ($supplier_id) {
    
    header('Location: index.php');
    exit;
}
?>
