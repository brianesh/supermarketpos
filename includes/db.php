<?php
$host = 'localhost';
$dbname = 'supermarketpos';
$username = 'root';
$password = 'password';


$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

return $mysqli;
?>
