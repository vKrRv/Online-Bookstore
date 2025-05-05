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
    <h1 class="title">All Books</h1>
    <?php if (isset($_GET['search']) && trim($_GET['search']) !== ''): ?>
        <p>Search results for "<strong><?php echo htmlspecialchars($_GET['search']); ?></strong>"</p>
    <?php endif; ?>

    <main>
        <div class="filters-container">
            <div class="filter-section">
                <form method="GET" action="" id="sortForm">
                    <span>Sort by:</span>
                    <select name="sort" onchange="document.getElementById('sortForm').submit()">
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
                    <span>Filter by:</span>
                    <select name="category" onchange="document.getElementById('filterForm').submit()">
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
                    showBookCard($book, '../');
                }
            } else {
            ?>
                <div class="no-books">
                    <i class="fas fa-book-open"></i>
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