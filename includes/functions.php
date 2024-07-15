<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "supermarketpos";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

function todaysSales($cashier_id = null) {
    global $mysqli;
    $query = "SELECT SUM(total_amount) AS total_sales FROM sales WHERE DATE(sale_date) = CURDATE()";
    if ($cashier_id !== null) {
        $query .= " AND user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $user_id);
    } else {
        $stmt = $mysqli->prepare($query);
    }
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['total_sales'] ?? 0;
}

function expiredProducts() {
    global $mysqli;
    $query = "SELECT COUNT(*) AS count FROM products WHERE expiration_date < CURDATE()";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    return $row['count'];
}

function Products() {
    global $mysqli;
    $query = "SELECT COUNT(*) AS count FROM products";
    $result = $mysqli->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'];
    } else {
        return 0;
    }
}

function suppliers() {
    global $mysqli;
    $query = "SELECT COUNT(*) AS count FROM suppliers";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    return $row['count'];
}

function users() {
    global $mysqli;
    $query = "SELECT COUNT(*) AS count FROM users";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    return $row['count'];
}

function getWeeksSales($cashier_id = null) {
    global $mysqli;
    $query = "SELECT SUM(total_amount) AS weeks_sales FROM sales WHERE WEEK(sale_date) = WEEK(NOW()) AND YEAR(sale_date) = YEAR(NOW())";
    if ($cashier_id !== null) {
        $query .= " AND user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $user_id);
    } else {
        $stmt = $mysqli->prepare($query);
    }
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['weeks_sales'] ?? 0;
}

function getMonthsSales($cashier_id = null) {
    global $mysqli;
    $query = "SELECT SUM(total_amount) AS months_sales FROM sales WHERE MONTH(sale_date) = MONTH(NOW()) AND YEAR(sale_date) = YEAR(NOW())";
    if ($cashier_id !== null) {
        $query .= " AND user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $user_id);
    } else {
        $stmt = $mysqli->prepare($query);
    }
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['months_sales'] ?? 0;
}

function getYearsSales($cashier_id = null) {
    global $mysqli;
    $query = "SELECT SUM(total_amount) AS years_sales FROM sales WHERE YEAR(sale_date) = YEAR(NOW())";
    if ($cashier_id !== null) {
        $query .= " AND user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $user_id);
    } else {
        $stmt = $mysqli->prepare($query);
    }
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['years_sales'] ?? 0;
}

function Stores() {
    global $mysqli;
    $query = "SELECT COUNT(*) AS count FROM company_details";
    $result = $mysqli->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'];
    } else {
        return 0; 
    }
}
?>
