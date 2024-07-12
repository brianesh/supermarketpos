<?php
session_start();
$mysqli = include('../../includes/db.php');
require_once('../../includes/functions.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = '';

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $query = "SELECT * FROM categories WHERE category_id='$category_id'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        $category = $result->fetch_assoc();
    } else {
        $error = "Category not found";
    }
} else {
    $error = "Category ID not provided";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];

    $query = "UPDATE categories SET category_name='$name' WHERE category_id='$category_id'";

    if ($mysqli->query($query) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error updating category: " . $mysqli->error;
    }
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category - FRESHMART POS</title>
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
            <h2>Edit Category</h2>
            <?php if (!empty($error)) : ?>
                <p class="error"><?php echo $error; ?></p>
            <?php else : ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                    
                    <label for="category_name">Category Name:</label>
                    <input type="text" id="category_name" name="category_name" value="<?php echo $category['name']; ?>" required><br><br>
                    <button type="submit" name="edit_category">Save Changes</button>
                </form>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
