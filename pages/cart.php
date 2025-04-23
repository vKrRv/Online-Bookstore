<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Helps adjust the website for smaller devices (Smartphones or tablets). -->
    <title>Shopping Cart - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- This library have lots of uses, but I'm using it specifically for "Magnifying glass icon" in search bar -->
</head>

<body>
<?php include '../includes/header.php'; ?>
    <h1 class="title">Shopping Cart</h1>
    <section id="cart">
        <div class="cart-item">
            <button class="remove-btn">×</button>
            <img src="../images/book1.png" alt="Book Cover" class="book-cover">
            <div class="item-details">
                <h3>Book Title</h3>
                <p>Author Name</p>
                <p class="symbol">Price: <strong>&#xea;</strong> 30</p>
                <input type="number" value="1" min="1">
            </div>
        </div>
        <div class="cart-item">
            <button class="remove-btn">×</button>
            <img src="../images/book2.png" alt="Book Cover" class="book-cover">
            <div class="item-details">
                <h3>Book Title</h3>
                <p>Author Name</p>
                <p class="symbol">Price: <strong>&#xea;</strong> 25</p>
                <input type="number" value="1" min="1">
            </div>
        </div>
        <div class="cart-item">
            <button class="remove-btn">×</button>
            <img src="../images/book3.png" alt="Book Cover" class="book-cover">
            <div class="item-details">
                <h3>Book Title</h3>
                <p>Author Name</p>
                <p class="symbol">Price: <strong>&#xea;</strong> 20</p>
                <input type="number" value="1" min="1">
            </div>
        </div>
        <div class="cart-item">
            <button class="remove-btn">×</button>
            <img src="../images/book4.png" alt="Book Cover" class="book-cover">
            <div class="item-details">
                <h3>Book Title</h3>
                <p>Author Name</p>
                <p class="symbol">Price: <strong>&#xea;</strong> 20</p>
                <input type="number" value="1" min="1">
            </div>
        </div>
        <div class="cart-item">
            <button class="remove-btn">×</button>
            <img src="../images/book5.png" alt="Book Cover" class="book-cover">
            <div class="item-details">
                <h3>Book Title</h3>
                <p>Author Name</p>
                <p class="symbol">Price: <strong>&#xea;</strong> 20</p>
                <input type="number" value="1" min="1">
            </div>
        </div>

    </section>
<div class="cart-btn">
<a href="../pages/products.php" class="back-shop-btn">Back to shopping</a>
<a href="checkout.php" class="checkout-btn">Continue to payment</a>
</div>

    <footer>
        <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
    </footer>
</body>
</html>