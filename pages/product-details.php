<?php
session_start();
unset($_SESSION['applied_coupon']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Book Haven</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php
    include '../includes/header.php';
    // Include db connection
    include '../includes/db.php';

    $message = "";
    $messageType = ""; // success or error

    // Sanitize book ID from URL
    $book_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($book_id) {
        // Fetch book details
        $query = "SELECT * FROM books WHERE book_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        // Check if book exists
        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();

            // Check if submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity'])) {
                $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

                // Basic validation
                if (!$quantity || $quantity < 1) {
                    $quantity = 1;
                }
                if ($quantity > $book['stock']) {
                    $quantity = $book['stock'];
                }

                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

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

                $message = "✅ Book added to cart successfully!";
                $messageType = "success";
            }
    ?>

            <div class="book-details">
                <div class="book-image">
                    <img src="../assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" />
                </div>
                <div class="book-info">
                    <h1><?php echo htmlspecialchars($book['title']); ?></h1>
                    <span class="category-tag"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($book['category']); ?></span>

                    <p class="description"><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>

                    <p class="price"><span class="symbol">&#xea;</span> <?php echo htmlspecialchars($book['price']); ?></p>

                    <div class="stock-container">
                        <?php if ($book['stock'] == 0): ?>
                            <p class="stock stock-out"><i class="fas fa-times-circle"></i> Out of Stock</p>
                        <?php elseif ($book['stock'] <= 5): ?>
                            <p class="stock low-stock"><i class="fas fa-exclamation-circle"></i> Only <?php echo htmlspecialchars($book['stock']); ?> left in
                                stock - order soon!</p>
                        <?php else: ?>
                            <p class="stock in-stock"><i class="fas fa-check-circle"></i> In Stock: <?php echo htmlspecialchars($book['stock']); ?> available</p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($message)): ?>
                        <div class="message-box <?php echo $messageType; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="purchase-controls">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($book['stock']); ?>">
                        <button type="submit" class="checkout-btn add-to-cart-green">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>

                    <a href="products.php" class="back-to-products"><i class="fas fa-arrow-left"></i> Back to All Books</a>
                </div>
            </div>

    <?php
        } else {
            // Book not found
            echo "<div class='error-message'><i class='fas fa-exclamation-triangle'></i><h2>Book not found</h2><p>The book you are looking for does not exist.</p><a href='products.php'><i class='fas fa-arrow-left'></i></a></div>";
        }

        $stmt->close();
    } else {
        // Invalid book ID
        echo "<div class='error-message'><i class='fas fa-exclamation-triangle'></i><h2>Invalid Request</h2><p>Please provide a valid book ID.</p><a href='products.php'><i class='fas fa-arrow-left'></i></a></div>";
    }
    // Close db connection
    $conn->close();
    ?>

    <?php include '../includes/footer.php'; ?>
</body>

</html>