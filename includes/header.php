<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calculate base URL 
$basePath = '';
$currentPath = $_SERVER['PHP_SELF'];
$depth = substr_count($currentPath, '/') - 1;

if (strpos($currentPath, '/pages/') !== false || strpos($currentPath, '/admin/') !== false) {
    $basePath = '../';
} else if (strpos($currentPath, '/includes/') !== false) {
    $basePath = '../';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Online Bookstore</title>
    <link href="<?php echo $basePath; ?>css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header class="main-header">
        <div class="header-container">
            <div class="nav-container">
                <nav class="main-nav">
                    <a href="<?php echo $basePath; ?>index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="<?php echo $basePath; ?>pages/products.php"><i class="fas fa-book"></i> Books</a>
                    <a href="<?php echo $basePath; ?>pages/cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                    <a href="<?php echo $basePath; ?>pages/contact.php"><i class="fas fa-envelope"></i> Contact</a>
                </nav>
            </div>

            <div class="search-container">
                <form class="search-form">
                    <input type="text" placeholder="Search for books..." />
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="user-actions">
                <?php if (isset($_SESSION['admin_username'])): ?>
                    <div class="user-welcome">
                        <i class="fas fa-user-shield"></i> Welcome, <?php echo $_SESSION['admin_username']; ?>
                    </div>
                    <a href="<?php echo $basePath; ?>admin/dashboard.php" class="login-btn">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="<?php echo $basePath; ?>admin/logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="<?php echo $basePath; ?>admin/login.php" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="<?php echo $basePath; ?>pages/signup.php" class="signup-btn">
                        <i class="fas fa-user-plus"></i> Sign up
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php
    ?>