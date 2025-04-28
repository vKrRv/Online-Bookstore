<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'bookstore');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if book ID is provided
if (isset($_GET['id'])) {
    $book_id = intval($_GET['id']);

    // Delete book from database
    $sql = "DELETE FROM books WHERE book_id = $book_id";

    if ($conn->query($sql) === TRUE) {
        echo "Book deleted successfully.";
    } else {
        echo "Error deleting book: " . $conn->error;
    }
} else {
    echo "No book ID provided.";
}

$conn->close();

// Redirect to dashboard after deletion
header("Location: dashboard.php");
exit();
?>
