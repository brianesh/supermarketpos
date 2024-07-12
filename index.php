<?php
session_start();
$servername = "localhost";
$username = "root"; 
$password = "password"; 
$dbname = "supermarketpos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$companyRegistered = false;
$sql = "SELECT * FROM company_details LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $companyRegistered = true;
}

if ($companyRegistered) {
    header("Location: login.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Company</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <div class="left-container">
        <div class="center-text">
            <span>WELCOME</span>
            <span>TO</span>
            <span>YOUR</span>
            <span>POS</span>
            <span>SYSTEM</span>
        </div>
        </div>
    <div class="right-container">
        <h2>Company Setup</h2>
        <form action="register.php" method="post" enctype="multipart/form-data">
            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" placeholder="Desired Company Name" required><br>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" placeholder="Your Phone Number" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Your Email" required><br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Your Address" required><br>
            <label for="logo">Upload Logo:</label>
            <input type="file" id="logo" name="logo" required><br>
            <button type="submit">Submit & Continue</button>
        </form>
    </div>
    </div>
</body>
<script>
        // Function to delay animation start
        function startAnimation() {
            setTimeout(function() {
                document.getElementById('welcome-text').classList.add('start');
            }, 500); // Delay before animation starts (milliseconds)
        }

        // Start animation on page load
        window.onload = function() {
            startAnimation();
        };
    </script>
</html>
