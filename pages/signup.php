<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Online Bookstore</title>
  <link href="../css/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

  <header>
    <img src="../images/logo.png" alt="Bookstore Logo" class="logo" />
    <div class="bar">
      <nav>
        <a href="../index.html">Home</a>
        <a href="products.html">Products</a>
        <a href="cart.html">Shopping Cart</a>
        <a href="contact.html">Contact Us</a>
      </nav>
      <div class="search-bar">
        <form>
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search for books..." />
        </form>
      </div>
      <nav>
        <a href="login.html" class="login">Login</a>
        <a href="signup.html" class="signup">Sign up</a>
      </nav>
    </div>
    <h1 class="title">Create Account</h1>
  </header>

  <section class="form-container">
    <form>
      <input type="text" placeholder="Full Name" required />
      <input type="email" placeholder="Email Address" required />
      <input type="text" placeholder="Username" required />
      <input type="password" placeholder="Password" required />
      <input type="password" placeholder="Confirm Password" required />
      <button type="submit">Create Account</button>
      <div style="text-align: center; margin-top: 10px;">
        <a href="login.html" style="color: #007BFF; text-decoration: none;">Already have an account? Login</a>
      </div>
    </form>
  </section>

  <footer>
    <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
  </footer>
</body>
</html>
