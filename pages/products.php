<?php
// Start session
session_start();
unset($_SESSION['applied_coupon']);
// Check if POST request is made to add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    require_once '../includes/db.php';
    include_once '../includes/functions.php';
    $book_id = filter_var($_POST['book_id'], FILTER_VALIDATE_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
    if (!$quantity || $quantity < 1) $quantity = 1;
    $book = getBookById($conn, $book_id);
    if ($book) {
        addToCart($book, $quantity);
        header('Location: cart.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Books - Book Haven</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <h1 class="title">All Books</h1>
    <main>
        <div class="filters-container">
            <div class="filter-section">
                <form method="GET" action="" id="sortForm">
                    <span>Sort by:</span>
                    <select name="sort" onchange="document.getElementById('sortForm').submit()"> <!-- Sort by dropdown -->
                        <option value="featured" <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'featured') ? 'selected' : ''; ?>>Featured</option>
                        <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                    <?php if (isset($_GET['category'])): ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                    <?php endif; ?>
                </form>
            </div>
            <div class="filter-section">
                <form method="GET" action="" id="filterForm">
                    <span>Filter by:</span>
                    <!-- Category filter -->
                    <select name="category" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" <?php echo (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'selected' : ''; ?>>All Categories</option>
                        <?php
                        // Check db connection
                        if (!isset($conn)) require_once '../includes/db.php';
                        require_once '../includes/functions.php';

                        // Fetch categories
                        $categories = getCategories($conn);
                        foreach ($categories as $category) {
                            $selected = (isset($_GET['category']) && $_GET['category'] == $category) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($category) . "' $selected>" . htmlspecialchars($category) . "</option>";
                        }
                        ?>
                    </select>

                    <?php if (isset($_GET['sort'])): ?>
                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($_GET['sort']); ?>">
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="book-grid">
            <?php
            // Include db connection
            require_once '../includes/db.php';
            include_once '../includes/functions.php';
            // Fetch books with filters
            $filters = [
                'category' => $_GET['category'] ?? 'all',
                'sort' => $_GET['sort'] ?? 'featured'
            ];
            $books = getAllBooks($conn, $filters);
            if (count($books) > 0) {
                foreach ($books as $book) {
            ?>
                    <div class="book-card">
                        <a href="product-details.php?id=<?php echo $book['book_id']; ?>" class="card-link">
                            <div class="card-img-container">
                                <img src="../assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="card-img">
                                <div class="view-details">View Details</div>
                            </div>
                        </a>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                            <span class="card-category"><?php echo htmlspecialchars($book['category']); ?></span>
                            <div class="card-price">
                                <span class="symbol">&#xea;</span><?php echo formatPrice($book['price']); ?>
                            </div>
                            <div class="card-stock <?php echo $book['stock'] == 0 ? 'stock-out' : ($book['stock'] <= 5 ? 'stock-low' : 'stock-in'); ?>">
                                <?php if ($book['stock'] == 0): ?>
                                    <i class="fas fa-times-circle"></i>
                                    <span>Out of Stock</span>
                                <?php elseif ($book['stock'] <= 5): ?>
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Only <?php echo htmlspecialchars($book['stock']); ?> left in stock</span>
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
            } else {
                ?>
                <div class="no-books">
                    <i class="fas fa-book-open"></i>
                    <p>No books available at the moment.</p>
                </div>
            <?php
            }

            // Close db connection
            $conn->close();
            ?>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>