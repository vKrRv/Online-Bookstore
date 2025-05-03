<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Book Haven</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="contact-container">
        <h1 class="title">Contact Us</h1>
        <div class="description">
            Have a question or feedback? We'd love to hear from you!
        </div>

        <div class="contact-content">
            <div class="contact-info-container">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Address</h3>
                            <div class="map-container">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3576.7763419801305!2d50.1938016!3d26.3049022!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e49e6f3daa43ffd%3A0x78d07850229b8ffe!2sKing%20Fahd%20Rd%2C%20Al%20Khobar%20Saudi%20Arabia!5e0!3m2!1sen!2s!4v1679834567890!5m2!1sen!2s"
                                    width="100%"
                                    height="220"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            <p>
                                <a href="https://maps.google.com/?q=King+Fahd+Road,+Khobar,+Saudi+Arabia"
                                    target="_blank"
                                    class="address-link">
                                    King Fahad Road, Khobar
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <p><a href="mailto:contactus@bookhaven.com" class="contact-email">contactus@bookhaven.com</a></p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h3>Phone</h3>
                            <p>+966 056 789 1230</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h3>Opening Hours</h3>
                            <p>Daily 9:00 AM - 10:00 PM</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-book-open"></i>
                        <div>
                            <h3>About Us</h3>
                            <p>We're passionate about books and dedicated to providing a wide selection of titles for
                                our customers.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-container">
                <h2>Send Us a Message</h2>
                <form class="contact-form" action="mailto:contactus@bookhaven.com" method="post" enctype="text/plain">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="Name" placeholder="Enter your name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="Email" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="Subject" placeholder="Enter the subject">
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="Message" placeholder="Write your message here..." required></textarea>
                    </div>

                    <button type="submit" class="contact-submit-btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>