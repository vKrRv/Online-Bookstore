<?php
// includes/functions.php

// Fetch a book by its ID
function getBookById($conn, $book_id) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
    return $book;
}

// Fetch all books, with optional filters (category, sort)
function getAllBooks($conn, $filters = []) {
    $query = "SELECT * FROM books";
    $params = [];
    $types = '';
    $where = [];

    if (!empty($filters['category']) && $filters['category'] !== 'all') {
        $where[] = "category = ?";
        $params[] = $filters['category'];
        $types .= 's';
    }
    if ($where) {
        $query .= " WHERE " . implode(' AND ', $where);
    }
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'price_asc':
                $query .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY price DESC";
                break;
            case 'featured':
            default:
                $query .= " ORDER BY featured DESC, book_id DESC";
                break;
        }
    } else {
        $query .= " ORDER BY featured DESC, book_id DESC";
    }
    $stmt = $conn->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    $stmt->close();
    return $books;
}

// Fetch stock for a book
function getBookStock($conn, $book_id) {
    $stmt = $conn->prepare("SELECT stock FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ? (int)$row['stock'] : 0;
}

// Add a book to the cart (session)
function addToCart($book, $quantity) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $book_id = $book['book_id'];
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$book_id] = [
            'book_id' => $book['book_id'],
            'title' => $book['title'],
            'price' => $book['price'],
            'quantity' => $quantity,
            'image' => $book['image']
        ];
    }
}
