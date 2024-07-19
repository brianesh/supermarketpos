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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $company_name = $conn->real_escape_string(trim($_POST["company_name"]));
    $phone = $conn->real_escape_string(trim($_POST["phone"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $address = $conn->real_escape_string(trim($_POST["address"]));
    $logo = $_FILES["logo"];

 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($logo["name"]);
    $uploadOk = 1;
    $errorMessages = [];

    
    $check = getimagesize($logo["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $errorMessages[] = "File is not an image.";
        $uploadOk = 0;
    }

    
    if (file_exists($targetFile)) {
        $errorMessages[] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    
    if ($logo["size"] > 500000) {
        $errorMessages[] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedFileTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFileTypes)) {
        $errorMessages[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        foreach ($errorMessages as $message) {
            echo "<p>$message</p>";
        }
    } else {
        if (move_uploaded_file($logo["tmp_name"], $targetFile)) {
            
            $stmt = $conn->prepare("INSERT INTO company_details (company_name, phone, email, address, logo) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $company_name, $phone, $email, $address, $targetFile);

            if ($stmt->execute()) {
                header("Location: adduser.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            
            echo "Sorry, there was an error uploading your file.";
        }}}

$conn->close();