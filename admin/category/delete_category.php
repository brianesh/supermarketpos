<?php
session_start();
$mysqli = include('../includes/db.php');
require_once('../includes/functions.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    $query = "DELETE FROM categories WHERE id='$category_id'";

    if ($mysqli->query($query) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error deleting category: " . $mysqli->error;
    }
} else {
    echo "Category ID not provided";
    exit;
}

$mysqli->close();
?>
