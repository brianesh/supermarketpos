<?php
session_start();
$mysqli = include('../includes/db.php');
require_once('../includes/functions.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['name'];

    $query = "INSERT INTO categories (name) VALUES ('$name')";

    if ($mysqli->query($query) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error adding category: " . $mysqli->error;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category - FRESHMART POS</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="sidebar">
        <!-- Sidebar content -->
    </div>
    <div class="main-content">
        <div class="header">
            <!-- Header content -->
        </div>
        <section>
            <h2>Add Category</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="name">Category Name:</label>
                <input type="text" id="name" name="name" required><br><br>
                <button type="submit" name="add_category">Add Category</button>
            </form>
        </section>
    </div>
</body>
</html>
