<?php
session_start();
unset($_SESSION['applied_coupon']);
require_once 'includes/db.php';
include_once 'includes/functions.php';
// Check if POST request is made to add to cart
if (addToCartPost($conn)) {
    header('Location: pages/cart.php');
    exit;
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
    <?php showRecent(); ?>
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
        
        if (count($featuredBooks) > 0) {
            foreach ($featuredBooks as $book) {
                if ($book['featured'] != 1) continue;
                if ($count >= 3) break;
                $count++;
                $isOutOfStock = $book['stock'] == 0;
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
                        <div class="card-stock <?php echo $isOutOfStock ? 'stock-out' : ($book['stock'] <= 5 ? 'stock-low' : 'stock-in'); ?>">
                            <?php if ($isOutOfStock): ?>
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
                            <button type="submit" name="add_to_cart" class="add-to-cart-btn" aria-label="Add '<?php echo $book['title']; ?>' to cart" <?php if ($isOutOfStock) echo 'disabled style="opacity: 0.5; cursor: not-allowed;"'; ?>>
                                <i class="fas fa-shopping-cart" aria-hidden="true"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                <?php
            }
        }

        if ($count === 0) {
            // No featured books were found
            echo "<div class='no-books' role='alert' aria-live='polite'><i class='fas fa-book-open' aria-hidden='true'></i><p>Featured books not found.</p></div>";
        }
        
        $conn->close();
        ?>
    </div>

    <div class="text-center">
        <a href="pages/products.php" class="view-all-btn" aria-label="View all books">View All Books</a>
    </div>
</section>
</main>
<?php include 'includes/footer.php'; ?>