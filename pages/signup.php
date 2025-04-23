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

  <?php include '../includes/header.php'; ?>
  <h1 class="title">Create an Account</h1>
  <section class="form-container">
    <form>
      <input type="text" placeholder="Full Name" required />
      <input type="email" placeholder="Email Address" required />
      <input type="text" placeholder="Username" required />
      <input type="password" placeholder="Password" required />
      <input type="password" placeholder="Confirm Password" required />
      <button type="submit">Create Account</button>
      <div style="text-align: center; margin-top: 10px;">
        <a href="../admin/login.php" style="color: #007BFF; text-decoration: none;">Already have an account? Login</a>
      </div>
    </form>
  </section>

  <footer>
    <p>&copy; 2025 Online Bookstore. All rights reserved.</p>
  </footer>
</body>
</html>
