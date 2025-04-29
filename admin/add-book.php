<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $image = trim($_POST['image']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    if ($title && $description && $image && is_numeric($price) && ctype_digit($stock)) {
        $stmt = $conn->prepare("INSERT INTO book (title, description, image, price, stock) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdi", $title, $description, $image, $price, $stock);

        if ($stmt->execute()) {
            header('Location: dashboard.php?success=1');
            exit();
        } else {
            $message = "Failed to add book.";
        }
    } else {
        $message = "Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link href="../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

    <h1 class="title">Add New Book</h1>

    <?php if ($message): ?>
        <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <section class="form-container">
        <form method="POST" action="add-book.php">
            <input type="text" name="title" placeholder="Title" required><br><br>

            <textarea name="description" placeholder="Description" required></textarea><br><br>

            <input type="text" name="image" placeholder="Image Name" required><br><br>

            <input type="text" name="price" placeholder="Price (e.g., 49.99)" required><br><br>

            <input type="text" name="stock" placeholder="Stock" required><br><br>

            <button type="submit">Add Book</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2025 Online Bookstore. Admin Panel.</p>
    </footer>
</body>
</html>
