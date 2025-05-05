<?php
session_start();
require_once '../includes/functions.php';

// Quantity increase/decrease
if (isset($_GET['action']) && isset($_GET['id'])) {
    $book_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($book_id && isset($_SESSION['cart'][$book_id])) {
        // Fetch stock
        require_once '../includes/db.php';
        $stock = 1;
        $stockResult = $conn->query("SELECT stock FROM books WHERE book_id = " . intval($book_id));
        // Check if result is valid
        if ($stockResult && $row = $stockResult->fetch_assoc()) {
            $stock = (int)$row['stock'];
        }
        // Increment/Decrement 
        if ($_GET['action'] === 'increment') {
            if ($_SESSION['cart'][$book_id]['quantity'] < $stock) {
                incrementCartItem($book_id);
            }
        } elseif ($_GET['action'] === 'decrement' && $_SESSION['cart'][$book_id]['quantity'] > 1) {
            decrementCartItem($book_id);
        }
        header('Location: cart.php');
        exit;
    }
}

// Remove item 
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $remove_id = filter_var($_GET['remove'], FILTER_VALIDATE_INT);
    if ($remove_id && isset($_SESSION['cart'][$remove_id])) {
        removeCartItem($remove_id);
        header('Location: cart.php');
        exit;
    }
}

// Quantity/Coupon update
$discount = 0;
$discountCode = '';
$discountError = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // if cart quantity is updated
    if (isset($_POST['update_cart'])) {
        updateQuantitiy($_POST['quantity']);
    }
    // if coupon is applied
    if (isset($_POST['apply_coupon'])) {
        // Remove any previously applied coupon
        unset($_SESSION['applied_coupon']);
        // Validate coupon 
        $discountCode = trim($_POST['coupon_code'] ?? '');
        if (strcasecmp($discountCode, 'FIRST15') === 0) {
            $_SESSION['applied_coupon'] = 'FIRST15';
        } else {
            $discountError = 'Invalid coupon code.';
        }
    }
    // if checkout is clicked
    if (isset($_POST['checkout'])) {
        header('Location: checkout.php');
        exit;
    }
}

if (isset($_SESSION['applied_coupon']) && $_SESSION['applied_coupon'] === 'FIRST15') {
    $discountCode = 'FIRST15'; // Check if coupon value equals FIRST15
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Book Haven</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="../js/utils.js"></script>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="cart-container">
        <h1 class="cart-title">
            Cart
            <span class="cart-count">
                <?php
                // Count items
                $itemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                echo $itemCount . ' item' . ($itemCount !== 1 ? 's' : '');
                ?>
            </span>
        </h1>
        <div class="blue-underline"></div>

        <?php
        // If cart is not empty
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $totalPrice = 0;
            $totalItems = 0;
            $shippingFee = 15.00;

            echo '<form method="post" action="cart.php">';
        ?>

            <div class="cart-flex-container">
                <div class="cart-items-container">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th style="width: 80px"></th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop and display cart items
                            foreach ($_SESSION['cart'] as $book_id => $item) {
                                $itemTotal = $item['price'] * $item['quantity'];
                                $totalPrice += $itemTotal;
                                $totalItems += $item['quantity'];

                                // Fetch stock
                                require_once '../includes/db.php';
                                $stock = 1;
                                $stockResult = $conn->query("SELECT stock FROM books WHERE book_id = " . intval($book_id));
                                if ($stockResult && $row = $stockResult->fetch_assoc()) {
                                    $stock = (int)$row['stock'];
                                }
                                // Delivery date
                                list($minDelivery, $maxDelivery) = getDeliveryDate();
                            ?>
                                <tr>
                                    <td>
                                        <div class="product-image">
                                            <a href="product-details.php?id=<?php echo $book_id; ?>">
                                                <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?> cover">
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-details">
                                            <div class="product-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                            <div class="product-delivery">
                                                <i class="fas fa-truck" aria-hidden="true"></i> Get it by <?php echo $minDelivery; ?> - <?php echo $maxDelivery; ?>
                                            </div>
                                            <div class="product-reserved">
                                                <i class="fas fa-info-circle" aria-hidden="true"></i> Reserved for 1 hour
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-price">
                                            <span class="symbol">&#xea;</span> <?php echo formatPrice($item['price']); ?>
                                        </div>
                                    </td>
                                    <td class="quantity-cell">
                                        <div class="quantity-control" role="group">
                                            <a href="cart.php?action=decrement&id=<?php echo $book_id; ?>" role="button" class="quantity-btn" aria-label="Increase quantity">−</a>
                                            <input type="number" name="quantity[<?php echo $book_id; ?>]"
                                                   value="<?php echo htmlspecialchars($item['quantity']); ?>"
                                                   min="1" max="<?php echo $stock; ?>" class="quantity-input"
                                                   aria-label="Quantity for <?php echo htmlspecialchars($item['title']); ?>: <?php echo htmlspecialchars($item['quantity']); ?>"
                                                   aria-live="polite" readonly>
                                            <a href="cart.php?action=increment&id=<?php echo $book_id; ?>" role="button" class="quantity-btn" aria-label="Decrease quantity">+</a>
                                        </div>
                                        <div id="quantity-announcement-<?php echo $book_id; ?>" class="visually-hidden" aria-live="polite">
                                            Quantity updated to <?php echo htmlspecialchars($item['quantity']); ?> for <?php echo htmlspecialchars($item['title']); ?>
                                        </div> <!-- This div block is for accessibility, it announces the user that the quantity updated via screen reader only -->

                                    </td>
                                    <td>
                                        <a href="cart.php?remove=<?php echo htmlspecialchars($book_id); ?>" class="remove-btn" title="Remove from cart">
                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="order-summary-container">
                    <div class="order-summary">
                        <h3 class="summary-title">Order Summary</h3>

                        <div class="coupon-input">
                            <input type="text" name="coupon_code" placeholder="Coupon Code" value="<?php echo htmlspecialchars($discountCode); ?>" aria-label="Enter coupon code">
                            <button type="submit" name="apply_coupon" aria-label="Apply coupon code">APPLY</button>
                        </div>
                        <?php if ($discountError): ?>
                            <div class="success-message" style="background:#ffe3e3;color:#e53e3e;" role="alert"><i class="fas fa-times-circle" aria-hidden="true"></i> <?php echo $discountError; ?></div>
                        <?php endif; ?>

                        <a href="#" class="offers-link" id="toggle-offers" aria-controls="available-offers" aria-expanded="false">
                            <div><i class="fas fa-tag" aria-hidden="true"></i> View Available Offers</div>
                            <i class="fas fa-chevron-right" id="offers-chevron" aria-hidden="true"></i>
                        </a>

                        <div class="available-offers" id="available-offers">
                            <div class="coupon-list">
                                <div class="coupon-item">
                                    <div>
                                        <div class="coupon-code">
                                            <i class="fas fa-ticket-alt" aria-hidden="true"></i>
                                            <strong>FIRST15</strong>
                                        </div>
                                        <div class="coupon-description">Get 15% off on your first order</div>
                                    </div>
                                    <button class="copy-button" onclick="event.preventDefault(); copyToClipboard('FIRST15', this)" aria-label="Copy coupon code FIRST15">COPY</button>
                                </div>
                            </div>
                        </div>

                        <div class="summary-row">
                            <div>Subtotal (<?php echo $totalItems; ?> item<?php echo $totalItems > 1 ? 's' : ''; ?>)</div>
                            <div><span class="symbol">&#xea;</span> <?php echo number_format($totalPrice, 2); ?></div>
                        </div>

                        <div class="summary-row">
                            <div>Shipping Fee</div>
                            <div><span class="symbol">&#xea;</span> <?php echo number_format($shippingFee, 2); ?></div>
                        </div>

                        <?php
                        // Calculate if code is applied
                        if ($discountCode === 'FIRST15') {
                            $discount = round($totalPrice * 0.15, 2);
                        ?>
                            <div class="summary-row" style="color:#38a169;">
                                <div>Discount (FIRST15)</div>
                                <div>-<span class="symbol">&#xea;</span> <?php echo number_format($discount, 2); ?></div>
                            </div>
                        <?php } ?>

                        <div class="summary-divider"></div>

                        <div class="summary-total">
                            <div>Total</div>
                            <div class="summary-total-price">
                                <span class="symbol">&#xea;</span> <?php echo number_format($totalPrice + $shippingFee - $discount, 2); ?>
                            </div>
                        </div>

                        <div class="payment-options">
                            <i class="fas fa-credit-card" aria-hidden="true"></i> Monthly payment plans available
                        </div>

                        <button type="submit" name="checkout" class="checkout-btn" aria-label="Proceed to checkout">CHECKOUT</button>
                    </div>
                </div>
            </div>

            </form>
        <?php
            // If cart is empty
        } else {
            echo '<div class="empty-cart" aria-live="polite">
                    <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven\'t added any books to your cart yet.</p>
                    <a href="../pages/products.php" class="browse-books-btn"><i class="fas fa-book" aria-hidden="true" style= "color: white"></i> Browse Books</a>
                  </div>';
        }
        ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>