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
<h1 class="title">Welcome to Book Haven</h1>
<section>
    <p class="description">Discover a world of knowledge and imagination with our carefully curated selection of
        books.<br>From bestsellers to hidden gems, we have something for every reader.</p>
</section>

<section>
    <?php showPastPurchases(); ?>
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
            if ($book['featured'] != 1) continue;
            if ($count >= 3) break;
            $count++;
            showBookCard($book, '');
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