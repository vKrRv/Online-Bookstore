<?php
session_start();

// Mock cart (Remove Later)
    $_SESSION['cart'] = array(
        2 => 4, // Book ID and quantity
        6 => 4  
    );


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$totalPrice = 0;
$shipping = 5.00;
$vat = 0;
$finalTotal = 0;
$bookDetails = [];
$errorMessage = "";

//cart total
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $bookId => $quantity) {
        $sql = "SELECT title, price FROM books WHERE book_id = $bookId";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            $row = mysqli_fetch_row($result);  //fetch data
            if ($row) {
                $bookPrice = $row[1]; // Fetch the price from the second column
                $bookTotal = $bookPrice * $quantity;
                $totalPrice += $bookTotal;
                
                $bookDetails[] = [
                    'title' => $row[0], // Fetch the title from the first column
                    'price' => $bookPrice,
                    'quantity' => $quantity,
                    'total' => $bookTotal,
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
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $sql = "INSERT INTO orders (customer_id, total_price, order_date) VALUES (NULL, $finalTotal, NOW())";
        
        if (mysqli_query($conn, $sql)) {
            // Replaced mysqli_insert_id with procedural method
            $orderId = mysqli_insert_id($conn);  // Fetch the last inserted ID using procedural style

            //process cart items
            foreach ($_SESSION['cart'] as $bookId => $quantity) {
                $sql = "SELECT price, stock FROM books WHERE book_id = $bookId";
                
                $result = mysqli_query($conn, $sql);
                
                if ($result) {
                    $row = mysqli_fetch_row($result);  // Using mysqli_fetch_row to fetch data
                    if ($row) {
                        $bookPrice = $row[0]; // Fetch the price from the first column
                        $currentStock = $row[1]; // Fetch the stock from the second column
                        
                        if ($currentStock >= $quantity) {
                            $newStock = $currentStock - $quantity;
                            $updateStockSql = "UPDATE books SET stock = $newStock WHERE book_id = $bookId";
                            
                            mysqli_query($conn, $updateStockSql);
                            
                            $orderItemSql = "INSERT INTO order_items (order_id, book_id, quantity, price) 
                                            VALUES ($orderId, $bookId, $quantity, $bookPrice)";
                            
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
                
                // Redirect to the bill page
                header("Location: bill.php?order_id=$orderId&total_price=$finalTotal&shipping=$shipping&vat=$vat");
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
    <title>Checkout</title>
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
                            <p><strong>Price:</strong> <?php echo $book['price']; ?><span class="symbol">&#xea;</span></p>
                            <p><strong>Quantity:</strong> <?php echo $book['quantity']; ?></p>
                            <hr class="short-hr">
                            <p><strong>Total: </strong> <?php echo $book['total']; ?><span class="symbol">&#xea;</span></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <p><strong>Total :</strong><span class="symbol">&#xea;</span> <?php echo $totalPrice; ?></p>
            <p><strong>Shipping:</strong><span class="symbol">&#xea;</span> <?php echo $shipping; ?></p>
            <p><strong>VAT (15%):</strong><span class="symbol">&#xea;</span> <?php echo $vat; ?></p>
            <hr class="short-hr">
            <p><strong>Total including VAT & Shipping: </strong> <span class="symbol">&#xea;</span> <?php echo $finalTotal; ?></p>
        <?php endif; ?>
    </div>
    <div class="payment-details">
        <h2>Card Details</h2>
        <hr><br>
        <form method="POST" action="">
            <label for="card-number"><strong>Card Number:</strong></label>
            <input type="text" id="card-number" placeholder="0000-0000-0000-0000" required />
            <br>
            <label for="expire"><strong>Expire date:</strong></label>
            <input type="month" id="expire" maxlength="5" placeholder="MM/YY" required />
            <br>
            <label for="cvv"><strong>CVV:</strong></label>
            <input type="text" id="cvv" maxlength="3" placeholder="000" required />
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
