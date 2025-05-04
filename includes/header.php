<?php
// Start session 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calculate base URL 
$basePath = '';
$currentPath = $_SERVER['PHP_SELF'];
$currentPage = basename($currentPath);
$depth = substr_count($currentPath, '/') - 1;
// Adjust based on current directory
if (strpos($currentPath, '/pages/') !== false || strpos($currentPath, '/admin/') !== false) {
    $basePath = '../';
} else if (strpos($currentPath, '/includes/') !== false) {
    $basePath = '../';
}
function isActive($page, $currentPage)
{
    return $page === $currentPage ? 'aria-current="page"' : '';
}//this function is to reduce repetition in nav bar


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Book Haven</title>
    <link href="<?php echo $basePath; ?>css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header class="main-header" role="banner">
        <div class="header-container">
            <div class="logo-container" style="display: flex; align-items: center; margin-right: 30px;">
                <a href="<?php echo $basePath; ?>index.php">
                    <img src="<?php echo $basePath; ?>assets/images/logo.png" alt="Book Haven Logo" height="90" width="180" style="display: block;" />
                </a>
            </div>
            <div class="nav-container">
                <nav class="main-nav">
                    <a href="<?php echo $basePath; ?>index.php" <?php echo isActive('index.php', $currentPage); ?>>
                        <i class="fas fa-home" aria-hidden="true"></i> Home
                    </a>
                    <a href="<?php echo $basePath; ?>pages/products.php" <?php echo isActive('products.php', $currentPage); ?>>
                        <i class="fas fa-book" aria-hidden="true"></i> Books
                    </a>
                    <a href="<?php echo $basePath; ?>pages/cart.php" <?php echo isActive('cart.php', $currentPage); ?>>
                        <i class="fas fa-shopping-cart" aria-hidden="true"></i> Cart
                    </a>
                    <a href="<?php echo $basePath; ?>pages/contact.php" <?php echo isActive('contact.php', $currentPage); ?>>
                        <i class="fas fa-envelope" aria-hidden="true"></i> Contact
                    </a>
                </nav>

            </div>

            <div class="search-container">
                <form class="search-form">
                    <label for="search-input" class="visually-hidden">Search for books</label>
                    <input type="text" id="search-input" name="query" placeholder="Search for books..." />
                    <button type="submit" aria-label="Search"><i class="fas fa-search" aria-hidden="true"></i></button>
                </form>
            </div>

            <div class="user-actions">
                <?php if (isset($_SESSION['admin_username'])): ?>
                    <span class="user-welcome"> <!-- Changed div to span -->
                        <i class="fas fa-user-shield" aria-hidden="true"></i> Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                    </span>
                    <a href="<?php echo $basePath; ?>admin/dashboard.php" class="login-btn">
                        <i class="fas fa-tachometer-alt" aria-hidden="true"></i> Dashboard
                    </a>
                    <a href="<?php echo $basePath; ?>admin/logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                    </a>
                <?php else: ?>
                    <!-- Placeholder to potentially maintain layout consistency -->
                    <span class="user-welcome-placeholder" style="display: inline-block; visibility: hidden; width: 0; height: 0; margin: 0; padding: 0; border: 0;"></span>
                    <a href="<?php echo $basePath; ?>admin/login.php" class="login-btn">
                        <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login
                    </a>
                    <a href="<?php echo $basePath; ?>pages/signup.php" class="signup-btn">
                        <i class="fas fa-user-plus" aria-hidden="true"></i> Sign up
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

</body>

</html>