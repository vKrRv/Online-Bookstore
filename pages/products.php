<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Books - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h1 class="title">All Books</h1>
    <main>
        <div class="sort-options">
            <form method="GET" action="" id="sortForm">
            <span>Sort by:</span>
            <select name="sort" onchange="document.getElementById('sortForm').submit()">
                <option value="featured" <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'featured') ? 'selected' : ''; ?>>Featured</option>
                <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
            </select>
            <?php if(isset($_GET['category'])): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
            <?php endif; ?>
            </form>
        </div>
        <div class="filter-options">
            <form method="GET" action="" id="filterForm">
            <span>Filter by:</span>
            <select name="category" onchange="document.getElementById('filterForm').submit()">
                <option value="all" <?php echo (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'selected' : ''; ?>>All Categories</option>
                <?php
                // Check if there is a db connection
                if(!isset($conn)) include '../includes/db.php';
                
                // Fetch distinct categories from the db
                $categoryQuery = "SELECT DISTINCT category FROM books ORDER BY category";
                $categoryResult = $conn->query($categoryQuery);
                
                // Display each category as an option
                while($categoryRow = $categoryResult->fetch_assoc()) {
                $selected = (isset($_GET['category']) && $_GET['category'] == $categoryRow['category']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($categoryRow['category']) . "' $selected>" . htmlspecialchars($categoryRow['category']) . "</option>";
                }
                ?>
            </select>
            <?php if(isset($_GET['sort'])): ?>
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($_GET['sort']); ?>">
            <?php endif; ?>
            </form>
        </div>

        <div class="book-list">
            <?php
            // Include db connection
            include '../includes/db.php';
            // Fetch all books from the db
            $query = "SELECT * FROM books";
            
            // Apply category filter
            if(isset($_GET['category']) && $_GET['category'] != 'all') {
                $category = $conn->real_escape_string($_GET['category']);
                $query .= " WHERE category = '$category'";
            }
            
            // Apply sorting filter
            if(isset($_GET['sort'])) {
                switch($_GET['sort']) {
                    case 'price_asc':
                        $query .= " ORDER BY price ASC";
                        break;
                    case 'price_desc':
                        $query .= " ORDER BY price DESC";
                        break;
                    case 'featured':
                    default:
                        $query .= " ORDER BY book_id DESC"; // New books first
                        break;
                }
            } else {
                $query .= " ORDER BY book_id DESC"; 
            }
            
            $result = $conn->query($query);
            
            // Check if there are any books
            if ($result->num_rows > 0) {
                // Loop and display each book
                while($book = $result->fetch_assoc()) {
                    ?>
                    <div class="book">
                        <a href="product-details.php?id=<?php echo $book['book_id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="book-image-container">
                                <img src="../assets/images/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>" class="book-cover">
                                <div class="show-details-btn">View Details</div>
                            </div>
                            <strong><?php echo $book['title']; ?></strong>
                        </a>
                        <p class="price"><span class="symbol">&#xea;</span> <?php echo $book['price']; ?></p>
                        <div class="stock-info">
                            <?php if($book['stock'] <= 5): ?>
                                <i class="fas fa-exclamation-circle low-stock"></i>
                                <p class="low-stock">Only <?php echo $book['stock']; ?> left</p>
                            <?php else: ?>
                                <i class="fas fa-check-circle in-stock"></i>
                                <p class="in-stock">In Stock</p>
                            <?php endif; ?>
                        </div>
                        <button class="add-to-cart-btn green">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='text-align: center; font-size: 1.3rem; margin: 50px 0;'>No books available at the moment.</p>";
            }
            
            // Close db connection
            $conn->close();
            ?>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>