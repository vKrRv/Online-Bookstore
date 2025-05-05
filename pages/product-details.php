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
    require_once '../includes/db.php';
    require_once '../includes/functions.php';

    $message = "";
    $messageType = "";

    $book_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($book_id) {
        $query = "SELECT * FROM books WHERE book_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity'])) {
                $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
                if (!$quantity || $quantity < 1) $quantity = 1;
                if ($quantity > $book['stock']) $quantity = $book['stock'];

                if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

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
                    <p class="price"><span class="symbol">&#xea;</span> <?php echo formatPrice($book['price']); ?></p>

                    <div class="stock-container with-help">
                        <?php if (isInStock($book)): ?>
                            <p class="stock in-stock"><i class="fas fa-check-circle"></i> In Stock: <?php echo htmlspecialchars($book['stock']); ?> available</p>
                        <?php else: ?>
                            <p class="stock stock-out"><i class="fas fa-times-circle"></i> Out of Stock</p>
                        <?php endif; ?>

                        <!-- Help Button beside stock -->
                        <button id="helpBtn" class="help-button-inline">Help ❓</button>
                    </div>

                    <?php if (!empty($message)): ?>
                        <div class="message-box <?php echo $messageType; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="purchase-controls">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($book['stock']); ?>" <?php if ($book['stock'] == 0) echo 'disabled'; ?>>
                        <button type="submit" class="checkout-btn add-to-cart-green" <?php if ($book['stock'] == 0) echo 'disabled style="opacity:0.5;cursor:not-allowed;"'; ?>>
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>

                    <a href="products.php" class="back-to-products"><i class="fas fa-arrow-left"></i> Back to All Books</a>
                </div>
            </div>

    <?php
        } else {
            echo "<div class='products-error-message'><i class='fas fa-exclamation-triangle'></i><h2>Book not found</h2><a href='products.php'><i class='fas fa-arrow-left'></i></a></div>";
        }

        $stmt->close();
    } else {
        echo "<div class='products-error-message'><i class='fas fa-exclamation-triangle'></i><h2>Invalid Request</h2><a href='products.php'><i class='fas fa-arrow-left'></i></a></div>";
    }

    $conn->close();
    ?>

    <?php include '../includes/footer.php'; ?>

    <!-- ✅ Help Popup Modal with FAQs -->
    <div id="helpPopup" class="help-popup">
        <div class="popup-content">
            <span id="closePopup" class="close-btn">&times;</span>
            <h3>Need Help?</h3>
            <p>Click a question to see the answer:</p>

            <div class="faq-item">
                <button class="faq-question">📘 What is the "Title"?</button>
                <div class="faq-answer">The title is the name of the book you’re viewing.</div>
            </div>

            <div class="faq-item">
                <button class="faq-question">💵 How is the price shown?</button>
                <div class="faq-answer">The price shows the cost for one copy of the book.</div>
            </div>

            <div class="faq-item">
                <button class="faq-question">📝 What does the description include?</button>
                <div class="faq-answer">It gives you a short summary of the book’s content or purpose.</div>
            </div>

            <div class="faq-item">
                <button class="faq-question">📦 What does stock status mean?</button>
                <div class="faq-answer">It shows how many copies are available right now.</div>
            </div>

            <div class="faq-item">
                <button class="faq-question">🛒 How do I add to cart?</button>
                <div class="faq-answer">Enter how many you want, then click "Add to Cart" to proceed.</div>
            </div>
        </div>
    </div>
    <script src="../js/popup.js"></script>
</body>

</html>