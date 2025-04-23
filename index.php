<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Online Bookstore</title>
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <h1 class="title">Welcome to Our Bookstore</h1>
    <section>
        <p class="description">Discover a world of knowledge and imagination with our carefully curated selection of books.<br>From bestsellers to hidden gems, we have something for every reader.</p>
    </section>
    <!-- Featured books section -->
    <section>
        <h2>Featured Books</h2>
        <div class="book-list">
            <?php
            // Include db connection
            include 'includes/db.php';
            
            // Fetch 4 random books from the database
            $query = "SELECT * FROM books ORDER BY RAND() LIMIT 4";
            $result = $conn->query($query);
            
            
            if ($result->num_rows > 0) {
                // Loop and display each book
                while($book = $result->fetch_assoc()) {
                    ?>
                    <div class="book">
                        <a href="pages/product-details.php?id=<?php echo $book['book_id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="book-image-container">
                                <img src="images/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>" class="book-cover">
                                <div class="show-details-btn">Show Details</div>
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
                    </div>
                    <?php
                }
            } else {
                echo "<p class='no-books'>No books available at the moment.</p>";
            }
            
            // Close db connection
            $conn->close();
            ?>
        </div>
        
        <div style="text-align: center;">
            <a href="pages/products.php" class="view-all-btn">View All Books</a>
        </div>
    </section>
    
    <footer class="index-footer">
        <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
    </footer>
</body>
</html>
