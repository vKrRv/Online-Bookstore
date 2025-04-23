<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Helps adjust the website for smaller devices (Smartphones or tablets). -->
    <title>Home - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- This library have lots of uses, but I'm using it specifically for "Magnifying glass icon" in search bar -->
</head>

<body>
    <header>
        <img src="../images/logo.jpg" alt="Bookstore Logo" class="logo" />
    <div class="bar">
        <nav>
            <a href="../index.php">Home</a>
            <a href="../pages/products.php">Products</a>
            <a href="../pages/cart.php">Shopping Cart</a>
            <a href="../pages/contact.php">Contact Us</a>
        </nav>
        <div class="search-bar">
            <form>
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search for books..." />
            </form>
        </div>
        <nav>
            <a href="../admin/login.php" class="login">Login</a>
            <a href="../pages/signup.php" class="signup">Sign up</a>
        </nav>
    </div>
</header>
</body>
</html>