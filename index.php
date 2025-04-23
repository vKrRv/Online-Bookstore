<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Helps adjust the website for smaller devices (Smartphones or tablets). -->
    <title>Home - Online Bookstore</title>
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- This library have lots of uses, but I'm using it specifically for "Magnifying glass icon" in search bar -->
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <h1 class="title">Welcome to Our Bookstore</h1>
    <section>
        <p class="description">Welcome to the best bookstore with the best prices!<br>
            Find all kinds of books here at unbeatable deals!</p>
    </section>

    <section>
        <h2>Popular Books</h2>
        <div class="book-list">
            <div class="book"> <strong>Book 1</strong> <br> A thrilling adventure novel. <br><a href="pages/product-details.php" class="btn">View</a></div>
            <div class="book"> <strong>Book 2</strong> <br> A must-read mystery book. <br><a href="pages/product-details.php" class="btn">View</a></div>
            <div class="book"> <strong>Book 3</strong> <br> An inspiring self-help guide. <br><a href="pages/product-details.php" class="btn">View</a></div>
        </div>
    </section>

    <footer class="index-footer">
        <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
    </footer>
</body>
</html>
