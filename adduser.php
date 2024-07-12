<?php
$mysqli = include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'admin'; 

   
    if (empty($full_name) || empty($email) || empty($phone) || empty($username) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

   
    $query = "INSERT INTO users (full_name, email, phone, username, password, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssss", $full_name, $email, $phone, $username, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "Admin user added successfully.";
        header('Location: activation.php');
        exit;
    } else {
        echo "Error: " . $mysqli->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin User - Supermarket POS</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <div class="left-container-image">
    <img src="./uploads/logo.png" alt="Company Logo" class="logo">
        </div>
        <div class="right-container">
    <header>
        <h2>ADMIN ACCOUNT</h2>
    </header>
    <main>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <div class="password-input">
    <input type="password" id="password" name="password" required>
    <span id="togglePassword" class="toggle-password" aria-label="Toggle password visibility">👁️</span>
</div>


            <label for="confirm_password">Confirm Password:</label>
            <div class="password-input">
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span id="togglePassword" class="toggle-password" aria-label="Toggle password visibility">👁️</span>
            </div>
            <button type="submit">Submit & Continue</button>
        </form>
    </main>
    </div>
    
    </div>
    
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const togglePasswordButtons = document.querySelectorAll("#togglePassword, #toggleConfirmPassword");

    togglePasswordButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            const inputField = this.previousElementSibling;
            const type = inputField.getAttribute("type") === "password" ? "text" : "password";
            inputField.setAttribute("type", type);
            this.textContent = type === "password" ? "👁️" : "🙈";
        });
    });
});
</script>

</html>
