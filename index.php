<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Helps adjust the website for smaller devices (Smartphones or tablets). -->
    <title>Home - Online Bookstore</title>
    <link href="./css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- This library have lots of uses, but I'm using it specifically for "Magnifying glass icon" in search bar -->
</head>

<body>
    <header>
        <img src="./images/logo.png" alt="Bookstore Logo" class="logo" />
    <div class="bar">
        <nav>
            <a href="index.html">Home</a>
            <a href="./pages/products.html">Products</a>
            <a href="./pages/cart.html">Shopping Cart</a>
            <a href="./pages/contact.html">Contact Us</a>
        </nav>
        <div class="search-bar">
            <form>
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search for books..." />
            </form>
        </div>
        <nav>
            <a href="./pages/login.html" class="login">Login</a>
            <a href="./pages/signup.html" class="signup">Sign up</a>
        </nav>
    </div>
        <h1 class="title">Welcome to Our Online Bookstore</h1>
    </header>
    <section>
        <p class="description">Welcome to the best bookstore with the best prices!<br>
            Find all kinds of books here at unbeatable deals!</p>
    </section>

    <section>
        <h2>Popular Books</h2>
        <div class="book-list">
            <div class="book"> <strong>Book 1</strong> <br> A thrilling adventure novel. <br><a href="./pages/product-details.html" class="btn">View</a></div>
            <div class="book"> <strong>Book 2</strong> <br> A must-read mystery book. <br><a href="./pages/product-details.html" class="btn">View</a></div>
            <div class="book"> <strong>Book 3</strong> <br> An inspiring self-help guide. <br><a href="./pages/product-details.html" class="btn">View</a></div>
        </div>
    </section>

    <footer class="index-footer">
        <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
    </footer>
</body>
</html>
