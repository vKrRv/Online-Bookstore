<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Helps adjust the website for smaller devices (Smartphones or tablets). -->
    <title>Product Details - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- This library have lots of uses, but I'm using it specifically for "Magnifying glass icon" in search bar -->
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="book-details">
    <img src="../images/book4.png" alt="book cover" class="book-cover" />
    <h1>Book Title</h1>
    <h4><strong>Author:</strong> Author Name</h4>
    <p><strong>Description:</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit.
        Amet architecto eos esse harum illum impedit itaque iusto maxime modi molestiae mollitia nemo nihil,
        nobis perspiciatis repudiandae veritatis vitae voluptas voluptatum.</p>
    <p class="symbol">Price: <strong>&#xea; 55</strong></p>
    <div>
        <input type="number" value="1" min="1">
        <button class="checkout-btn"><a href="cart.php" class="btn-text">Continue to payment</a></button>
    </div>

</div>


<footer class="index-footer">
    <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
</footer>
</body>
</html>