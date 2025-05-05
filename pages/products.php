<?php
// Start session
session_start();
unset($_SESSION['applied_coupon']);
require_once '../includes/db.php';

include_once '../includes/functions.php';
if (addToCartPost($conn)) {
    header('Location: cart.php');
    exit;
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
    <main role="main">
        <h1 class="title">All Books</h1>
        
        <?php if (isset($_GET['search']) && trim($_GET['search']) !== ''): ?>
            <p>Search results for "<strong><?php echo htmlspecialchars($_GET['search']); ?></strong>"</p>
        <?php endif; ?>

        <div class="filters-container">
            <div class="filter-section">
                <form method="GET" action="" id="sortForm">
                    <label for="sortSelect">Sort by:</label>
                    <select name="sort" id="sortSelect" onchange="document.getElementById('sortForm').submit()">
                        <option value="featured" <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'featured') ? 'selected' : ''; ?>>Featured</option>
                        <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                    <?php if (isset($_GET['category'])): ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                    <?php endif; ?>
                </form>
            </div>

            <div class="filter-section">
                <form method="GET" action="" id="filterForm">
                    <label for="filterSelect">Filter by:</label>
                    <select name="category" id="filterSelect" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" <?php echo (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'selected' : ''; ?>>All Categories</option>
                        <?php
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
                    <?php if (isset($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="book-grid">
            <?php
            $params = [
                'search' => $_GET['search'] ?? '',
                'category' => $_GET['category'] ?? 'all',
                'sort' => $_GET['sort'] ?? 'featured'
            ];
            $result = getFilteredBooks($conn, $params);
            if ($result && $result->num_rows > 0) {
                while ($book = $result->fetch_assoc()) {
                    ?>
                    <div class="book-card">
                        <a href="product-details.php?id=<?php echo $book['book_id']; ?>" class="card-link" aria-label="View details of <?php echo htmlspecialchars($book['title']); ?>">
                            <div class="card-img-container">
                                <img src="../assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?> cover" class="card-img">
                                <div class="view-details">View Details</div>
                            </div>
                        </a>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                            <span class="card-category"><?php echo htmlspecialchars($book['category']); ?></span>
                            <div class="card-price" aria-label="Price: <?php echo $book['price']; ?> riyal">
                                <span class="symbol">&#xea;</span><?php echo htmlspecialchars($book['price']); ?>
                            </div>
                            <div class="card-stock <?php echo $book['stock'] == 0 ? 'stock-out' : ($book['stock'] <= 5 ? 'stock-low' : 'stock-in'); ?>">
                                <?php if ($book['stock'] == 0): ?>
                                    <i class="fas fa-times-circle" aria-hidden="true"></i>
                                    <span>Out of Stock</span>
                                <?php elseif ($book['stock'] <= 5): ?>
                                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                    <span>Only <?php echo htmlspecialchars($book['stock']); ?> left in stock</span>
                                <?php else: ?>
                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                    <span>In Stock</span>
                                <?php endif; ?>
                            </div>
                            <form method="post" style="margin-top:10px;">
                                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn" aria-label="Add '<?php echo htmlspecialchars($book['title']); ?>' to cart">
                                    <i class="fas fa-shopping-cart" aria-hidden="true"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="no-books" role="alert" aria-live="polite">
                    <i class="fas fa-book-open" aria-hidden="true"></i>
                    <p>
                        <?php
                        $search = trim($_GET['search'] ?? '');
                        echo !empty($search)
                            ? 'No results found for "' . htmlspecialchars($search) . '".'
                            : 'No books available at the moment.';
                        ?>
                    </p>
                </div>
            <?php
            }
            $conn->close();
            ?>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>

</html>