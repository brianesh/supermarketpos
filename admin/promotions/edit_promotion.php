<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header('Location: ../../index.php');
    exit;
}

// Include database connection
require_once '../../includes/db.php';

// Initialize variables for form input
$id = $_GET['id'] ?? '';
$title = $description = $start_date = $end_date = '';
$errors = [];

// Fetch promotion details based on ID
$sql = "SELECT * FROM promotions WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $promotion = $result->fetch_assoc();
    $title = $promotion['title'];
    $description = $promotion['description'];
    $start_date = $promotion['start_date'];
    $end_date = $promotion['end_date'];
} else {
    $_SESSION['error'] = 'Promotion not found.';
    header('Location: index.php'); // Redirect to promotions index if promotion not found
    exit;
}

$stmt->close();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Basic validation
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    if (empty($description)) {
        $errors[] = 'Description is required.';
    }

    // If no errors, update in database
    if (empty($errors)) {
        $sql = "UPDATE promotions SET title = ?, description = ?, start_date = ?, end_date = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ssssi', $title, $description, $start_date, $end_date, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Promotion updated successfully.';
            $stmt->close();
            $mysqli->close();
            header('Location: index.php'); // Redirect to promotions index after updating
            exit;
        } else {
            $_SESSION['error'] = 'Failed to update promotion.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Promotion - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <h1>Edit Promotion</h1>
        <nav>
            <ul>
                <li><a href="index.php">Back to Promotions</a></li>
                <li><a href="../../admin/index.php">Admin Dashboard</a></li>
                <li><a href="../../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Edit Promotion Details</h2>
            <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $id); ?>">
                <label for="title">Title:</label><br>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br><br>
                
                <label for="description">Description:</label><br>
                <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea><br><br>
                
                <label for="start_date">Start Date:</label><br>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>"><br><br>
                
                <label for="end_date">End Date:</label><br>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>"><br><br>
                
                <input type="submit" value="Update Promotion">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> FRESHMART POS. All rights reserved.</p>
    </footer>
</body>
</html>
