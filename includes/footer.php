<?php
require_once __DIR__ . '/functions.php';
$footerBasePath = getBasePath();
?>
<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-section about-section">
            <h3>About Book Haven</h3>
            <p>Your destination for quality books with worldwide shipping. Discover new worlds through our carefully
                curated collection.</p>
            <div class="social-icons">
                <a href="#" aria-label="Facebook"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-x-twitter" aria-hidden="true"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                <a href="#" aria-label="TikTok"><i class="fab fa-tiktok" aria-hidden="true"></i></a>
            </div>
        </div>

        <div class="footer-section links-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="<?php echo $footerBasePath; ?>index.php">Home</a></li>
                <li><a href="<?php echo $footerBasePath; ?>pages/products.php">All Books</a></li>
                <li><a href="<?php echo $footerBasePath; ?>pages/cart.php">Shopping Cart</a></li>
                <li><a href="<?php echo $footerBasePath; ?>pages/contact.php">Contact Us</a></li>
            </ul>
        </div>

        <div class="footer-section customer-section">
            <h3>Customer Service</h3>
            <ul>
                <li><a href="#">Shipping Policy</a></li>
                <li><a href="#">Return Policy</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

        <div class="footer-section newsletter-section">
            <h3>Stay Updated</h3>
            <p>Subscribe to our newsletter for the latest releases and exclusive offers.</p>
            <form class="newsletter-form">
                <label for="newsletter-email" class="visually-hidden">Email address</label>
                <input type="email" id="newsletter-email" name="newsletter_email" placeholder="Your Email Address" required>
                <button type="submit"><i class="fas fa-paper-plane" aria-hidden="true"></i></button>
            </form>
            <div class="payment-methods">
                <img src="<?php echo $footerBasePath; ?>assets/cards/visa.svg" alt="Visa" class="payment-icon">
                <img src="<?php echo $footerBasePath; ?>assets/cards/mastercard.svg" alt="Mastercard"
                    class="payment-icon">
                <img src="<?php echo $footerBasePath; ?>assets/cards/apple-pay.svg" alt="Apple Pay"
                    class="payment-icon">
                <img src="<?php echo $footerBasePath; ?>assets/cards/mada.svg" alt="Mada" class="payment-icon">
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 Book Haven. All rights reserved.</p>
    </div>
</footer>

</body>

</html>