<?php
include '../includes/db.php';

if (isset($_GET['id'])) {
    $book_id = (int) $_GET['id'];

    
    $conn->query("DELETE FROM order_items WHERE book_id = $book_id");

    
    $delete = "DELETE FROM books WHERE book_id = $book_id";

    if ($conn->query($delete) === TRUE) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error deleting book: " . $conn->error;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
