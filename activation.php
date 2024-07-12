<?php
session_start();

$mysqli = include('./includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'];
    $activation_key = $_POST['activation_key'];

    if (empty($company_name) || empty($activation_key)) {
        echo "All fields are required.";
        exit;
    }

    $query = "SELECT * FROM activation_keys WHERE company_name = ? AND activation_key = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $company_name, $activation_key);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        
        $_SESSION['company_name'] = $company_name;
        header('Location: login.php');
        exit();
    } else {
        echo "Invalid company name or activation key.";
    }

    $stmt->close();
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Activation</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
    <div class="container">
    <div class="left-container-image">
    <img src="uploads/logo.png" alt="Company Logo" class="logo">
        </div>
    <div class="right-container">
    <header>
        <h1>Product Activation</h1>
    </header>
    <main>
        <form method="post" action="">
            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" required><br><br>

            <label for="activation_key">Activation Key:</label>
            <input type="text" id="activation_key" name="activation_key" required><br><br>

            <button type="submit">Activate Product</button>
        </form>
    </main>
    </div>
</div>
    </body>
</body>
</html>
