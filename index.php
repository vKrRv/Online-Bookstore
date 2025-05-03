<?php
session_start();
unset($_SESSION['applied_coupon']);
// Check if POST request is made to add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    require_once 'includes/db.php';
    include_once 'includes/functions.php';
    $book_id = filter_var($_POST['book_id'], FILTER_VALIDATE_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
    if (!$quantity || $quantity < 1) $quantity = 1;
    $book = getBookById($conn, $book_id);
    if ($book) {
        addToCart($book, $quantity);
        header('Location: pages/cart.php');
        exit;
    }
}
?>
<?php
$pageTitle = "Home - Book Haven";
include 'includes/header.php';
?>
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
        require_once 'includes/db.php';
        include_once 'includes/functions.php';
        // Fetch featured books
        $featuredBooks = getAllBooks($conn, ['sort' => 'featured']);
        $count = 0;
        foreach ($featuredBooks as $book) {
            if ($count >= 3) break;
            $count++;
        ?>
                    <div class="book-card">
                        <a href="pages/product-details.php?id=<?php echo $book['book_id']; ?>" class="card-link">
                            <div class="card-img-container">
                                <div class="featured-tag">
                                    <i class="fas fa-star"></i> Featured
                                </div>
                                <img src="assets/images/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>"
                                    class="card-img">
                                <div class="view-details">View Details</div>
                            </div>
                        </a>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $book['title']; ?></h3>
                            <span class="card-category"><?php echo $book['category']; ?></span>
                            <div class="card-price">
                                <span class="symbol">&#xea;</span><?php echo $book['price']; ?>
                            </div>
                            <div class="card-stock <?php echo $book['stock'] == 0 ? 'stock-out' : ($book['stock'] <= 5 ? 'stock-low' : 'stock-in'); ?>">
                                <?php if ($book['stock'] == 0): ?>
                                    <i class="fas fa-times-circle"></i>
                                    <span>Out of Stock</span>
                                <?php elseif ($book['stock'] <= 5): ?>
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Only <?php echo $book['stock']; ?> left in stock</span>
                                <?php else: ?>
                                    <i class="fas fa-check-circle"></i>
                                    <span>In Stock</span>
                                <?php endif; ?>
                            </div>
                            <form method="post" style="margin-top:10px;">
                                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
        <?php
        }
        if ($count === 0) {
            echo "<div class='no-books'><i class='fas fa-book-open'></i><p>Featured books not found.</p></div>";
        }
        $conn->close();
        ?>
    </div>

    <div class="text-center">
        <a href="pages/products.php" class="view-all-btn">View All Books</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>