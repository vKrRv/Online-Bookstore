<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php 
    session_start();
    include '../includes/header.php'; 
    ?>

    <?php
    include '../includes/db.php';

    $message = "";
    $messageType = ""; // success or error

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $book_id = $_GET['id'];

        $query = "SELECT * FROM books WHERE book_id = $book_id";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity'])) {
                $quantity = intval($_POST['quantity']);
                
                if ($quantity < 1) {
                    $message = "⚠️ Value must be greater than or equal to 1.";
                    $messageType = "error";
                } elseif ($quantity > $book['stock']) {
                    $message = "⚠️ Sorry, not enough stock available. Only {$book['stock']} left.";
                    $messageType = "error";
                } else {
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
            }
            ?>

            <div class="book-details">
                <div class="book-image">
                    <img src="../assets/images/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>" />
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

                    <?php if (!empty($message)): ?>
                        <div class="message-box <?php echo $messageType; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="purchase-controls">
                        <input type="number" name="quantity" value="1"  >
                        <button type="submit" class="checkout-btn">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>

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

    $conn->close();
    ?>
    
    <?php include '../includes/footer.php'; ?>
</body>

</html>
