<?php
session_start();
include '../includes/db.php';

$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
$totalPrice = isset($_GET['total_price']) ? $_GET['total_price'] : 0;
$shipping = isset($_GET['shipping']) ? $_GET['shipping'] : 5.00;
$vat = isset($_GET['vat']) ? $_GET['vat'] : 0;

//information from form data (if coming from POST)
$customerName = isset($_POST['ship_name']) ? $_POST['ship_name'] : '';
$addressLine1 = isset($_POST['ship_line1']) ? $_POST['ship_line1'] : '';
$addressLine2 = isset($_POST['ship_line2']) ? $_POST['ship_line2'] : '';
$city = isset($_POST['ship_city']) ? $_POST['ship_city'] : '';
$postalCode = isset($_POST['ship_postal']) ? $_POST['ship_postal'] : '';
$customerPhone = isset($_POST['ship_phone']) ? $_POST['ship_phone'] : '';
$customerEmail = isset($_POST['ship_email']) ? $_POST['ship_email'] : '';

//If customer info isn't in POST, check if it's in the session from checkout.php
if (empty($customerName) && isset($_SESSION['ship_name'])) {
    $customerName = $_SESSION['ship_name'];
    $addressLine1 = $_SESSION['ship_line1'];
    $addressLine2 = $_SESSION['ship_line2'];
    $city = $_SESSION['ship_city'];
    $postalCode = $_SESSION['ship_postal'];
    $customerPhone = $_SESSION['ship_phone'];
    $customerEmail = $_SESSION['ship_email'];
}

//order details from the database
$orderItems = [];
if ($orderId > 0) {
    // Get order items
    $sql = "SELECT oi.book_id, oi.quantity, oi.price, b.title, b.image 
            FROM order_items oi
            JOIN books b ON oi.book_id = b.book_id
            WHERE oi.order_id = $orderId";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orderItems[] = [
                'title' => $row['title'],
                'price' => $row['price'],
                'quantity' => $row['quantity'],
                'total' => $row['price'] * $row['quantity'],
                'image' => $row['image']
            ];
        }
    }
}

// Calculate subtotal from order items (in case we're not getting it from GET)
if (empty($totalPrice) && !empty($orderItems)) {
    $subtotal = 0;
    foreach ($orderItems as $item) {
        $subtotal += $item['total'];
    }
    $vat = $subtotal * 0.15;
    $totalPrice = $subtotal + $shipping + $vat;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Book Haven</title>
    <link href="../css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="order-confirmation">
        <h1>Thank You for Your Order!</h1>

        <div class="order-details">
            <h1>Order Details</h1>
            <hr>
            <p><strong>Order Number:</strong> #<?php echo $orderId; ?></p>
            <p><strong>Order Date:</strong> <?php echo date('F j, Y'); ?></p>
            <h2>Ordered Items:</h2>

            <?php if (!empty($orderItems)): ?>
                <div class="ordered-items">
                    <?php foreach ($orderItems as $item): ?>
                        <div class="ordered-item">
                            <?php if (!empty($item['image'])): ?>
                                <img src="../assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="small-book-image">
                            <?php endif; ?>
                            <div class="ordered-item-details">
                                <p><strong><?php echo $item['title']; ?></strong></p>
                                <p class="symbol"><strong>Price:</strong> &#xea; <?php echo number_format($item['price'], 2); ?> </p>
                                <p><strong>Quantity:</strong> <?php echo $item['quantity']; ?></p>
                                <p class="symbol"><strong>Item Total:</strong> &#xea; <?php echo number_format($item['total'], 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No items found for this order.</p>
            <?php endif; ?>

            <hr class="short-hr">
            <div class="order-summary-totals">
                <p class="symbol"><strong>Subtotal:</strong> <span>&#xea; <?php echo number_format($totalPrice - $shipping - $vat, 2); ?></span></p>
                <p class="symbol"><strong>Shipping:</strong> <span>&#xea; <?php echo number_format($shipping, 2); ?></span></p>
                <p class="symbol"><strong>VAT (15%):</strong> <span>&#xea; <?php echo number_format($vat, 2); ?></span></p>
                <p class="symbol final-total"><strong>Total:</strong> <span>&#xea; <?php echo number_format($totalPrice, 2); ?></span></p>
            </div>
        </div>

        <div class="customer-info">
            <h1>Customer Information</h1>
            <hr>
            <?php if (!empty($customerName)): ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($customerName); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($customerEmail); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($customerPhone); ?></p>
                <p><strong>Shipping Address:</strong>
                    <?php
                    echo htmlspecialchars($addressLine1);
                    if (!empty($addressLine2)) echo ", " . htmlspecialchars($addressLine2);
                    echo ", " . htmlspecialchars($city) . ", " . htmlspecialchars($postalCode);
                    ?>
                </p>
            <?php else: ?>
                <!-- Display from session if available -->
                <p><strong>Name:</strong> <?php echo isset($_SESSION['customer_name']) ? htmlspecialchars($_SESSION['customer_name']) : 'Name'; ?></p>
                <p><strong>Email:</strong> <?php echo isset($_SESSION['customer_email']) ? htmlspecialchars($_SESSION['customer_email']) : 'username@example.com'; ?></p>
                <p><strong>Phone Number:</strong> <?php echo isset($_SESSION['customer_phone']) ? htmlspecialchars($_SESSION['customer_phone']) : '+966 00 000 0000'; ?></p>
                <p><strong>Shipping Address:</strong> <?php echo isset($_SESSION['customer_address']) ? htmlspecialchars($_SESSION['customer_address']) : '123 Main St, City, State, ZIP'; ?></p>
            <?php endif; ?>
        </div>

        <div class="thank-you">
            <p>Your order has been confirmed and will be processed soon!</p>
        </div>

        <div class="action-buttons">
            <a href="../index.php" class="button btn">Continue Shopping</a>
            <button onclick="window.print()" class="button btn-secondary">Print Receipt</button>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        //simple animation to show the confirmation is complete
        document.addEventListener('DOMContentLoaded', function() {
            const thankYou = document.querySelector('.thank-you');
            thankYou.style.opacity = '0';
            setTimeout(function() {
                thankYou.style.transition = 'opacity 1s ease-in';
                thankYou.style.opacity = '1';
            }, 300);
        });
    </script>
</body>

</html>