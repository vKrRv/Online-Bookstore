<?php
require_once __DIR__ . '/functions.php';
$basePath = getBasePath();
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
    <header class="main-header">
        <div class="header-container">
            <div class="logo-container" style="display: flex; align-items: center; margin-right: 30px;">
                <a href="<?php echo $basePath; ?>index.php">
                    <img src="<?php echo $basePath; ?>assets/images/logo.png" alt="Book Haven Logo" height="90" width="180" style="display: block;" />
                </a>
            </div>
            <div class="nav-container">
                <nav class="main-nav">
                    <a href="<?php echo $basePath; ?>index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="<?php echo $basePath; ?>pages/products.php"><i class="fas fa-book"></i> Books</a>
                    <a href="<?php echo $basePath; ?>pages/cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                    <a href="<?php echo $basePath; ?>pages/contact.php"><i class="fas fa-envelope"></i> Contact</a>
                </nav>
            </div>

            <div class="search-container">
                <form class="search-form" action="<?php echo $basePath; ?>pages/products.php" method="get">
                    <input type="text" name="search" placeholder="Search for books..."
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="user-actions">
                <?php if (isset($_SESSION['admin_username'])): ?>
                    <span class="user-welcome"> <!-- Changed div to span -->
                        <i class="fas fa-user-shield"></i> Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                    </span>
                    <a href="<?php echo $basePath; ?>admin/dashboard.php" class="login-btn">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="<?php echo $basePath; ?>admin/logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <!-- Placeholder to potentially maintain layout consistency -->
                    <span class="user-welcome-placeholder" style="display: inline-block; visibility: hidden; width: 0; height: 0; margin: 0; padding: 0; border: 0;"></span>
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

</body>

</html>