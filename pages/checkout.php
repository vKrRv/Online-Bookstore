<?php
session_start();
unset($_SESSION['applied_coupon']);
// Include database connection
include '../includes/db.php';

$totalPrice = 0;
$shipping = 5.00;
$vat = 0;
$finalTotal = 0;
$bookDetails = [];
$errorMessage = "";

//cart total
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (!isset($item['book_id']) || !isset($item['quantity'])) continue; // skip invalid
        $bookId = $item['book_id'];
        $quantity = $item['quantity'];
        $sql = "SELECT title, price, image FROM books WHERE book_id = $bookId";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_row($result);
            if ($row) {
                $bookPrice = $row[1];
                $bookTotal = $bookPrice * $quantity;
                $totalPrice += $bookTotal;
                $bookDetails[] = [
                    'title' => $row[0],
                    'price' => $bookPrice,
                    'quantity' => $quantity,
                    'total' => $bookTotal,
                    'image' => $row[2]
                ];
            } else {
                $errorMessage = "Book ID $bookId not found in the database.";
            }
        } else {
            $errorMessage = "Query failed for Book ID $bookId.";
        }
    }
    $vat = $totalPrice * 0.15;
    $finalTotal = $totalPrice + $shipping + $vat;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get shipping address information
    $shipName = isset($_POST['ship_name']) ? $_POST['ship_name'] : '';
    $shipLine1 = isset($_POST['ship_line1']) ? $_POST['ship_line1'] : '';
    $shipLine2 = isset($_POST['ship_line2']) ? $_POST['ship_line2'] : '';
    $shipCity = isset($_POST['ship_city']) ? $_POST['ship_city'] : '';
    $shipPostal = isset($_POST['ship_postal']) ? $_POST['ship_postal'] : '';
    $shipPhone = isset($_POST['ship_phone']) ? $_POST['ship_phone'] : '';
    $shipEmail = isset($_POST['ship_email']) ? $_POST['ship_email'] : '';
    
    // Store shipping info in session
    $_SESSION['customer_name'] = $shipName;
    $_SESSION['customer_address'] = $shipLine1 . ($shipLine2 ? ", $shipLine2" : "") . ", $shipCity, $shipPostal";
    $_SESSION['customer_phone'] = $shipPhone;
    $_SESSION['customer_email'] = $shipEmail;
    
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $sql = "INSERT INTO orders (customer_id, total_price, order_date) VALUES (NULL, $finalTotal, NOW())";
        if (mysqli_query($conn, $sql)) {
            $orderId = mysqli_insert_id($conn);
            foreach ($_SESSION['cart'] as $item) {
                if (!isset($item['book_id']) || !isset($item['quantity'])) continue;
                $bookId = $item['book_id'];
                $quantity = $item['quantity'];
                $sql = "SELECT price, stock FROM books WHERE book_id = $bookId";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $row = mysqli_fetch_row($result);
                    if ($row) {
                        $bookPrice = $row[0];
                        $currentStock = $row[1];
                        if ($currentStock >= $quantity) {
                            $newStock = $currentStock - $quantity;
                            $updateStockSql = "UPDATE books SET stock = $newStock WHERE book_id = $bookId";
                            mysqli_query($conn, $updateStockSql);
                            $orderItemSql = "INSERT INTO order_items (order_id, book_id, quantity, price) VALUES ($orderId, $bookId, $quantity, $bookPrice)";
                            mysqli_query($conn, $orderItemSql);
                        } else {
                            $errorMessage = "Not enough stock for book ID: $bookId";
                        }
                    }
                }
            }
            if (empty($errorMessage)) {
                unset($_SESSION['cart']);
                $_SESSION['total_price'] = 0;
                
                // Redirect to bill.php with all necessary information
                $redirectUrl = "bill.php?order_id=$orderId&total_price=$finalTotal&shipping=$shipping&vat=$vat";
                header("Location: $redirectUrl");
                exit();
            }
        } else {
            $errorMessage = "Error creating order: " . mysqli_error($conn);
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
                                    <p class="book-price-co"><strong>Price:</strong> <span class="symbol">&#xea;</span><?php echo $book['price']; ?></p>
                                    <p class="book-quantity"><strong>Quantity:</strong> <?php echo $book['quantity']; ?></p>
                                    <p class="book-subtotal"><strong>Total:</strong> <span class="symbol">&#xea;</span><?php echo $book['total']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <hr>
                    
                    <div class="total-summary">
                        <p><strong>Subtotal:</strong> <span><span class="symbol">&#xea;</span><?php echo $totalPrice; ?></span></p>
                        <p><strong>Shipping:</strong> <span><span class="symbol">&#xea;</span><?php echo $shipping; ?></span></p>
                        <p><strong>VAT (15%):</strong> <span><span class="symbol">&#xea;</span><?php echo $vat; ?></span></p>
                        <p class="final-total"><strong>Total:</strong> <span><span class="symbol">&#xea;</span><?php echo $finalTotal; ?></span></p>
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
                    <img src="../assets/cards/tabby.svg" alt="Tabby" class="payment-icon-co">
                </div>
                
                <form method="POST" action="" id="payment-form">
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
                        <span>PAY <br> <span class="symbol">&#xea;</span><?php echo $finalTotal;?></span>
                    </button>
                    
                    <button type="button" class="checkout-btn-pl" onclick="payLater()">
                        <span>PAY Later<br><span class="symbol">&#xea;</span><?php echo $finalTotal;?></span>
                    </button>
                </form>
            </div>
        </div>

        <div class="cart-btn">
            <a href="cart.php" class="back-shop-btn">← Back to cart</a>
        </div>
    </div>
    
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
        // Copy values from shipping form to payment form hidden fields
        document.getElementById('form-ship-name').value = document.getElementById('ship-name').value;
        document.getElementById('form-ship-line1').value = document.getElementById('ship-line1').value;
        document.getElementById('form-ship-line2').value = document.getElementById('ship-line2').value;
        document.getElementById('form-ship-city').value = document.getElementById('ship-city').value;
        document.getElementById('form-ship-postal').value = document.getElementById('ship-postal').value;
        document.getElementById('form-ship-phone').value = document.getElementById('ship-phone').value;
        document.getElementById('form-ship-email').value = document.getElementById('ship-email').value;
    }
    
    function payLater() {
        // Copy shipping details first
        copyShippingDetails();
        
        // Then submit the form
        document.getElementById('payment-form').submit();
    }
    </script>
    
</body>

</html>