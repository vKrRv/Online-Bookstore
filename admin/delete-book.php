<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) { // get id from url
    $book_id = (int) $_GET['id']; // security measure

    
    $conn->query("DELETE FROM order_items WHERE book_id = $book_id"); // delete query

    
    $delete = "DELETE FROM books WHERE book_id = $book_id"; // delete query

    if ($conn->query($delete) === TRUE) {
        header('Location: dashboard.php'); // redirect to dashboard
        exit;
    } else {
        echo "Error deleting book: " . $conn->error; 
    }
} else { //invalid request
    header('Location: dashboard.php'); // redirect to dashboard
    exit;
}
?>
