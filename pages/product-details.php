<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- This library have lots of uses, but I'm using it specifically for "Magnifying glass icon" in search bar -->
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <?php
    // Include db connection
    include '../includes/db.php';

    // See if we have a book ID in the URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $book_id = $_GET['id'];

        // Fetch book details from the db
        $query = "SELECT * FROM books WHERE book_id = $book_id";
        $result = $conn->query($query);

        // Check if it exists
        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();
            ?>
            <div class="book-details">
                <div class="book-image">
                    <img src="../images/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>" />
                </div>
                <div class="book-info">
                    <h1><?php echo $book['title']; ?></h1>
                    <span class="category-tag"><i class="fas fa-tag"></i> <?php echo $book['category']; ?></span>

                    <p class="description"><strong>Description:</strong><br><?php echo $book['description']; ?></p>

                    <p class="price"><span class="symbol">&#xea;</span> <?php echo $book['price']; ?></p>

                    <?php if ($book['stock'] <= 5): ?>
                        <p class="low-stock"><i class="fas fa-exclamation-circle"></i> Only <?php echo $book['stock']; ?> left in
                            stock - order soon!</p>
                    <?php else: ?>
                        <p class="stock"><i class="fas fa-check-circle"></i> In Stock: <?php echo $book['stock']; ?> available</p>
                    <?php endif; ?>

                    <div class="purchase-controls">
                        <input type="number" value="1" min="1" max="<?php echo $book['stock']; ?>">
                        <a href="cart.php?id=<?php echo $book['book_id']; ?>" class="checkout-btn">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </a>
                    </div>

                    <a href="products.php" class="back-to-products"><i class="fas fa-arrow-left"></i> Back to All Books</a>
                </div>
            </div>
            <?php
        } else {
            echo "<div class='error-message'><i class='fas fa-exclamation-triangle'></i><h2>Book not found</h2><p>The book you are looking for does not exist.</p><a href='products.php'><i class='fas fa-arrow-left'></i></a></div>";
        }
    } else {
        echo "<div class='error-message'><i class='fas fa-exclamation-triangle'></i><h2>Invalid Request</h2><p>Please provide a valid book ID.</p><a href='products.php'><i class='fas fa-arrow-left'></i></a></div>";
    }

    // Close db connection
    $conn->close();
    ?>

    <footer class="index-footer">
        <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
    </footer>
</body>

</html>