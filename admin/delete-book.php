<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $book_id = (int) $_POST['id'];
    $conn->query("DELETE FROM order_items WHERE book_id = $book_id");
    $delete = "DELETE FROM books WHERE book_id = $book_id";
    if ($conn->query($delete) === TRUE) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error deleting book: " . $conn->error;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>
