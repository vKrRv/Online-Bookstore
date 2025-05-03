<?php
session_start();
unset($_SESSION['applied_coupon']);
require_once '../includes/db.php';
require_once '../includes/functions.php';

$shipping = 5.00;
$vat = 0;
$finalTotal = 0;
$errorMessage = "";

// Calculate cart totals using utility function
$cartTotals = calculateCartTotals($conn);
$totalPrice = $cartTotals['totalPrice'];
$bookDetails = $cartTotals['bookDetails'];
$vat = $totalPrice * 0.15;
$finalTotal = $totalPrice + $shipping + $vat;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        list($orderId, $errorMessage) = createOrder($conn, $_SESSION['cart'], $finalTotal);
        if ($orderId && empty($errorMessage)) {
            unset($_SESSION['cart']);
            $_SESSION['total_price'] = 0;
            header("Location: bill.php?order_id=$orderId&total_price=$finalTotal&shipping=$shipping&vat=$vat");
            exit();
        }
    } else {
        $errorMessage = "Cart is empty! Please add items to your cart before proceeding.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Book Haven</title>
    <link href="../css/style.css" rel="stylesheet" />
</head>

<body>
    <div class="payment">
        <h1>Payment</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="order-error-msg"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <hr><br>
            <?php if (empty($bookDetails)): ?>
                <p class="order-error-msg">Your cart is empty.</p>
            <?php else: ?>
                <div class="book-list">
                    <?php foreach ($bookDetails as $book): ?>
                        <div class="book-item">
                            <div class="book-details">
                                <strong><?php echo $book['title']; ?></strong>
                                <p><strong>Price:</strong> <?php echo formatPrice($book['price']); ?><span class="symbol">&#xea;</span></p>
                                <p><strong>Quantity:</strong> <?php echo $book['quantity']; ?></p>
                                <hr class="short-hr">
                                <p><strong>Total: </strong> <?php echo formatPrice($book['total']); ?><span class="symbol">&#xea;</span></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p><strong>Total :</strong><span class="symbol">&#xea;</span> <?php echo formatPrice($totalPrice); ?></p>
                <p><strong>Shipping:</strong><span class="symbol">&#xea;</span> <?php echo formatPrice($shipping); ?></p>
                <p><strong>VAT (15%):</strong><span class="symbol">&#xea;</span> <?php echo formatPrice($vat); ?></p>
                <hr class="short-hr">
                <p><strong>Total including VAT & Shipping: </strong> <span class="symbol">&#xea;</span> <?php echo formatPrice($finalTotal); ?></p>
            <?php endif; ?>
        </div>
        <div class="payment-details">
            <h2>Card Details</h2>
            <hr><br>
            <form method="POST" action="">
                <label for="card-number"><strong>Card Number:</strong></label>
                <input type="text" id="card-number" placeholder="0000-0000-0000-0000" />
                <br>
                <label for="expire"><strong>Expire date:</strong></label>
                <input type="month" id="expire" placeholder="MM/YY" />
                <br>
                <label for="cvv"><strong>CVV:</strong></label>
                <input type="text" id="cvv" placeholder="000" />
                <br><br>
                <button type="submit" class="checkout-btn">Confirm payment</button>
            </form>
        </div>

        <div class="cart-btn">
            <a href="cart.php" class="back-shop-btn">Back to cart</a>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
    </footer>
</body>

</html>