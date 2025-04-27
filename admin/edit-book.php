<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'bookstore');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if book ID is provided
if (isset($_POST['update'])) {
    $book_id = intval($_POST['book_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = $conn->real_escape_string($_POST['category']);
    $image = $conn->real_escape_string($_POST['image']); // يفترض أنك تدير رفع الصورة بمكان ثاني

    // update book in database
    $sql = "UPDATE books 
            SET title='$title', description='$description', price=$price, stock=$stock, category='$category'
            WHERE book_id=$book_id";

    if ($conn->query($sql) === TRUE) {
        echo "Book updated successfully.";
    } else {
        echo "Error updating book: " . $conn->error;
    }

    header("Location: dashboard.php");
    exit();
} elseif (isset($_GET['id'])) {
    $book_id = intval($_GET['id']);

    // Fetch book details from database
    $result = $conn->query("SELECT * FROM books WHERE book_id = $book_id");

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "Book not found.";
        exit();
    }
} else {
    echo "No book ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <h1 class="title">Edit Book</h1>

    <section class="admin-section">
        <h3>Update Book Details</h3>
        <form class="admin-form" method="post" action="edit-book.php">
            <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
            <input type="text" name="title" placeholder="Book Title" value="<?= htmlspecialchars($book['title']) ?>" required>
            <textarea name="description" placeholder="Description" required><?= htmlspecialchars($book['description']) ?></textarea>
            <input type="number" name="price" placeholder="Price (e.g., 49.99)" step="0.01" value="<?= $book['price'] ?>" required>
            <input type="number" name="stock" placeholder="Stock" value="<?= $book['stock'] ?>" required>
            <input type="text" name="category" placeholder="Category" value="<?= htmlspecialchars($book['category']) ?>" required>
            <input type="text" name="image" placeholder="Image filename (e.g., book.jpg)" value="<?= htmlspecialchars($book['image']) ?>">
            <button type="submit" name="update">Update Book</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2025 Online Bookstore. Admin Panel.</p>
    </footer>

</body>

</html>