<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
        <link href="../css/style.css" rel="stylesheet" /> 
</head>
<body>
    <div class="order-confirmation">
        <h1>Thank You for Your Order!</h1>
        <div class="order-details">
            <h2>Order Details</h2>
            <hr>
            <p><strong>Order Number:</strong> #1</p>
            <p><strong>Items Ordered:</strong></p>
            <p class="symbol">Book A - &#xea; 49.95 </p>
            <hr class="short-hr">
            <p class="symbol"><strong>Shipping:</strong>&#xea; 5.00 </p>
            <p class="symbol"><strong>VAT (15%):</strong>&#xea; 8.25 </p> <!-- 15% of 55.00 Riyals -->
            <p class="symbol"><strong>Total:</strong>&#xea; 63.20 </p> <!-- Updated total -->
        </div>
        <div class="customer-info">
            <h2>Customer Information</h2>
            <hr>
            <p><strong>Name:</strong> Name</p>
            <p><strong>Email:</strong> username@example.com</p>
            <p><strong>Phone Number</strong> +966 00 000 0000</p>

            <p><strong>Shipping Address:</strong> <address>123 Main St, City, State, ZIP</address></p>
        </div>
        <div class="thank-you">
            <p role="status">Your order has been Confirmed</p>
        </div>
        <a href="../index.php" class="button btn" aria-label="Continue shopping on the homepage">Continue Shopping</a>
    </div>
    <footer>
        <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
    </footer>
</body>
</html>