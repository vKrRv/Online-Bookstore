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
        // Store shipping details in session
        $_SESSION['ship_name'] = $_POST['ship_name'];
        $_SESSION['ship_line1'] = $_POST['ship_line1'];
        $_SESSION['ship_line2'] = $_POST['ship_line2'];
        $_SESSION['ship_city'] = $_POST['ship_city'];
        $_SESSION['ship_postal'] = $_POST['ship_postal'];
        $_SESSION['ship_phone'] = $_POST['ship_phone'];
        $_SESSION['ship_email'] = $_POST['ship_email'];

        list($orderId, $errorMessage) = createOrder($conn, $_SESSION['cart'], $finalTotal);
        if ($orderId && empty($errorMessage)) {
            // Save purchased books to cookie before clearing cart
            $purchasedBooks = [];
            foreach ($_SESSION['cart'] as $item) {
                $purchasedBooks[] = [
                    'book_id' => $item['book_id'],
                    'title' => $item['title'],
                    'image' => $item['image']
                ];
            }
            addToPastPurchases($purchasedBooks);
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
        <h1>Checkout</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="order-error-msg"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="checkout-container">
            <div class="order-summary-co">
                <h2>Order Summary</h2>
                <hr>
                <br>
                <?php if (empty($bookDetails)): ?>
                    <p class="order-error-msg">Your cart is empty.</p>
                <?php else: ?>
                    <div class="book-list-co">
                        <?php foreach ($bookDetails as $book): ?>
                            <div class="book-item">
                                <div class="book-image-co">
                                    <img src="../assets/images/<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>">
                                </div>
                                <div class="book-details-co">
                                    <h3><?php echo $book['title']; ?></h3>
                                    <p class="book-price-co"><strong>Price:</strong> <span class="symbol">&#xea;</span><?php echo formatPrice($book['price']); ?></p>
                                    <p class="book-quantity"><strong>Quantity:</strong> <?php echo $book['quantity']; ?></p>
                                    <p class="book-subtotal"><strong>Total:</strong> <span class="symbol">&#xea;</span><?php echo formatPrice($book['total']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <div class="total-summary">
                        <p><strong>Subtotal:</strong> <span><span class="symbol">&#xea;</span><?php echo formatPrice($totalPrice); ?></span></p>
                        <p><strong>Shipping:</strong> <span><span class="symbol">&#xea;</span><?php echo formatPrice($shipping); ?></span></p>
                        <p><strong>VAT (15%):</strong> <span><span class="symbol">&#xea;</span><?php echo formatPrice($vat); ?></span></p>
                        <p class="final-total"><strong>Total:</strong> <span><span class="symbol">&#xea;</span><?php echo formatPrice($finalTotal); ?></span></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="shipping-address">
                <h2>Shipping Address</h2>
                <hr>
                <form id="shipping-form">
                    <label for="ship-name">Full Name</label>
                    <input type="text" id="ship-name" name="ship_name" placeholder="Full Name" required />
                    <label for="ship-line1">Address Line 1</label>
                    <input type="text" id="ship-line1" name="ship_line1" placeholder="123 Main St" required />
                    <label for="ship-line2">Address Line 2</label>
                    <input type="text" id="ship-line2" name="ship_line2" placeholder="Apt, Suite, etc. (optional)" />
                    <label for="ship-city">City</label>
                    <input type="text" id="ship-city" name="ship_city" placeholder="Riyadh" required />
                    <label for="ship-postal">Postal Code</label>
                    <input type="text" id="ship-postal" name="ship_postal" maxlength="5" placeholder="12345" required />
                    <label for="ship-phone">Phone Number</label>
                    <input type="text" id="ship-phone" name="ship_phone" maxlength="10" placeholder="050 000 0000" required />
                    <label for="ship-email">Email Address</label>
                    <input type="email" id="ship-email" name="ship_email" placeholder="example@email.com" required />
                </form>
            </div>
            <div class="payment-details">
                <h2>Payment Method</h2>
                <hr>
                <p><strong>Pay with Credit Card</strong></p>
                <div class="payment-methods-co">
                    <img src="../assets/cards/visa.svg" alt="Visa" class="payment-icon-co">
                    <img src="../assets/cards/mastercard.svg" alt="Mastercard" class="payment-icon-co">
                    <img src="../assets/cards/apple-pay.svg" alt="Apple Pay" class="payment-icon-co">
                    <!-- <img src="../assets/cards/mada.svg" alt="Mada" class="payment-icon-co"> -->
                    <img src="../assets/cards/tabby.svg" alt="Tabby" class="payment-icon-tabby">
                </div>
                <form method="POST" action="" id="payment-form" class="payform">
                    <!-- Hidden fields to include shipping information -->
                    <input type="hidden" id="form-ship-name" name="ship_name" value="">
                    <input type="hidden" id="form-ship-line1" name="ship_line1" value="">
                    <input type="hidden" id="form-ship-line2" name="ship_line2" value="">
                    <input type="hidden" id="form-ship-city" name="ship_city" value="">
                    <input type="hidden" id="form-ship-postal" name="ship_postal" value="">
                    <input type="hidden" id="form-ship-phone" name="ship_phone" value="">
                    <input type="hidden" id="form-ship-email" name="ship_email" value="">
                    <label for="card-number"><strong>Card Number</strong></label>
                    <input type="text" id="card-number" placeholder="0000 0000 0000 0000" maxlength="19" oninput="formatCardNumber()" required />
                    <label for="expire"><strong>Expire date</strong></label>
                    <input type="month" id="expire" required />
                    <label for="cvv"><strong>CVV</strong></label>
                    <input type="text" id="cvv" maxlength="3" placeholder="000" required />
                    <button type="submit" class="checkout-btn-co" onclick="copyShippingDetails()">
                        <span>PAY <br> <span class="symbol">&#xea;</span><?php echo formatPrice($finalTotal);?></span>
                    </button>
                    <button type="button" class="checkout-btn-pl" onclick="payLater()">
                        <span>PAY Later<br><span class="symbol">&#xea;</span><?php echo formatPrice($finalTotal);?></span>
                    </button>
                </form>
            </div>
        </div>
        <div class="cart-btn">
            <a href="cart.php" class="back-shop-btn">← Back to cart</a>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
    </footer>
    <script>
    function formatCardNumber() {
        const input = document.getElementById('card-number');
        let v = input.value.replace(/\D/g, '');
        let out = '';
        for (let i=0; i<v.length; i++) {
            if (i>0 && i%4===0) out += ' ';
            out += v[i];
        }
        input.value = out.slice(0,19);
    }
    function copyShippingDetails() {
        document.getElementById('form-ship-name').value = document.getElementById('ship-name').value;
        document.getElementById('form-ship-line1').value = document.getElementById('ship-line1').value;
        document.getElementById('form-ship-line2').value = document.getElementById('ship-line2').value;
        document.getElementById('form-ship-city').value = document.getElementById('ship-city').value;
        document.getElementById('form-ship-postal').value = document.getElementById('ship-postal').value;
        document.getElementById('form-ship-phone').value = document.getElementById('ship-phone').value;
        document.getElementById('form-ship-email').value = document.getElementById('ship-email').value;
    }
    function payLater() {
        copyShippingDetails();
        document.getElementById('payment-form').submit();
    }
    </script>
</body>
</html>