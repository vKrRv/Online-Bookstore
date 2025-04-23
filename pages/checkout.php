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
    <div class="payment-details">
        <h2>Card Details</h2>
        <hr>
        <form>
            <label for="card-number"><strong>Card Number:</strong></label>
            <input type="text" id="card-number" placeholder="0000-0000-0000-0000"/>
            <br>
            <label for="expire"><strong>Expire date:</strong></label>
            <input type="month" id="expire" placeholder="MM/YY" />
            <br>
            <label for="cvv"><strong>CVV:</strong></label>
            <input type="text" id="cvv" maxlength="3" placeholder="000"/>
            <p class="symbol">Book A - &#xea; 49.95 </p>
            <hr class="short-hr">
            <p class="symbol"><strong>Shipping:</strong>&#xea; 5.00 </p>
            <p class="symbol"><strong>VAT (15%):</strong>&#xea; 8.25 </p> <!-- 15% of 55.00 Riyals -->
            <p class="symbol"><strong>Total:</strong>&#xea; 63.20 </p> <!-- Updated total -->
        </form>
    </div>
    <div class="customer-info">
        <h2>Customer Information</h2>
        <hr>
        <p><strong>Name:</strong> Name</p>
        <p><strong>Email:</strong> username@example.com</p>
        <p><strong>Phone Number:</strong> +966 00 000 0000</p>
        <label for="address"><strong>Shipping Address:</strong></label>
        <input type="text" id="address" value="123 Main St, City, State, ZIP" placeholder="Enter shipping address">
    </div>
    <div class="cart-btn">
        <button class="back-shop-btn"><a href="cart.html" class="btn-text">Back to cart</a></button>
        <button class="checkout-btn"><a href="bill.html" class="btn-text">Confirm payment</a></button>
    </div>
</div>
<footer>
    <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
</footer>
</body>
</html>