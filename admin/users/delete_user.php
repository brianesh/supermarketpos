<?php
session_start();
$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    $query = "SELECT * FROM users WHERE user_id='$user_id' AND role='cashier'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        
        $delete_query = "DELETE FROM users WHERE user_id='$user_id'";
        
        if ($mysqli->query($delete_query) === TRUE) {
            header('Location: index.php');
            exit;
        } else {
            echo "Error deleting user: " . $mysqli->error;
        }
    } else {
        echo "Cannot delete admin user.";
        exit;
    }
} else {
    echo "User ID not provided";
    exit;
}

$mysqli->close();
?>
