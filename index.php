<?php
session_start();
unset($_SESSION['applied_coupon']);
// Check if POST request is made to add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    include 'includes/db.php';
    // Validate input
    $book_id = filter_var($_POST['book_id'], FILTER_VALIDATE_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
    if (!$quantity || $quantity < 1) $quantity = 1;
    $book = $conn->query("SELECT * FROM books WHERE book_id = $book_id")->fetch_assoc();
    // Check if book exists
    if ($book) {
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
        header('Location: pages/cart.php');
        exit;
    }
}
?>
<?php
$pageTitle = "Home - Book Haven";
include 'includes/header.php';
?>
<main role="main">
<h1 class="title">Welcome to Book Haven</h1>
<section>
    <p class="description">Discover a world of knowledge and imagination with our carefully curated selection of
        books.<br>From bestsellers to hidden gems, we have something for every reader.</p>
</section>

<section>
    <h2>Featured Books</h2>

    <div class="book-grid">
        <?php
        // Include db connection
        include 'includes/db.php';
        // Fetch featured books
        $query = "SELECT * FROM books WHERE featured = 1 LIMIT 3";
        $result = $conn->query($query);

        if ($result) {

            if ($result->num_rows > 0) {
                // Loop and display
                while ($book = $result->fetch_assoc()) {
        ?>
                    <div class="book-card">
                        <a href="pages/product-details.php?id=<?php echo $book['book_id']; ?>" class="card-link" aria-label="View details of <?php echo $book['title']; ?>">
                            <div class="card-img-container">
                                <div class="featured-tag">
                                    <i class="fas fa-star" aria-hidden="true"></i> Featured
                                </div>
                                <img src="assets/images/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?> cover"
                                    class="card-img">
                                <div class="view-details">View Details</div>
                            </div>
                        </a>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $book['title']; ?></h3>
                            <span class="card-category"><?php echo $book['category']; ?></span>
                            <div class="card-price" aria-label="Price: <?php echo $book['price']; ?> riyal">
                                <span class="symbol">&#xea;</span><?php echo $book['price']; ?>
                            </div>
                            <div class="card-stock <?php echo $book['stock'] == 0 ? 'stock-out' : ($book['stock'] <= 5 ? 'stock-low' : 'stock-in'); ?>">
                                <?php if ($book['stock'] == 0): ?>
                                    <i class="fas fa-times-circle" aria-hidden="true"></i>
                                    <span>Out of Stock</span>
                                <?php elseif ($book['stock'] <= 5): ?>
                                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                    <span>Only <?php echo $book['stock']; ?> left in stock</span>
                                <?php else: ?>
                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                    <span>In Stock</span>
                                <?php endif; ?>
                            </div>
                            <form method="post" style="margin-top:10px;">
                                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn" aria-label="Add '<?php echo $book['title']; ?>' to cart">
                                    <i class="fas fa-shopping-cart" aria-hidden="true"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
        <?php
                } // end while
            } else {
                // No featured books were found
                echo "<div class='no-books' role='alert' aria-live='polite'><i class='fas fa-book-open' aria-hidden='true'></i><p>Featured books not found.</p></div>";
            }
        }
        // Close db connection
        $conn->close();
        ?>
    </div>

    <div class="text-center">
        <a href="pages/products.php" class="view-all-btn" aria-label="View all books">View All Books</a>
    </div>
</section>
</main>
<?php include 'includes/footer.php'; ?>