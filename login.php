<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $mysqli = include('includes/db.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    
    if (empty($username) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } else {
        
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];

               
                if ($user['role'] === 'admin') {
                    header('Location: admin/index.php');
                    exit;
                } elseif ($user['role'] === 'cashier') {
                    header('Location: cashier/index.php');
                    exit;
                }
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }

        
        $stmt->close();
    }

   
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Supermarket POS</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <div class="left-container-image">
    <img src="uploads/logo.png" alt="Company Logo" class="logo">
        </div>
    <div class="right-container">
    <header>
        <h1>Login to Supermarket POS</h1>
    </header>

    <main>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="cashier">Cashier</option>
            </select><br><br>

            <button type="submit">Login</button>
        </form>
    </main>
    </div>
</div>
    <footer>
        <p>&copy; 2024 Supermarket POS. All rights reserved.</p>
    </footer>
</body>
</html>
