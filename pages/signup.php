<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Book Haven</title>
  <link href="../css/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>

  <?php include '../includes/header.php'; ?>

  <div class="signup-container">
    <div class="auth-icon">
      <i class="fas fa-user-plus"></i>
    </div>
    <h1 class="title">Create an Account</h1>

    <div class="signup-form">
      <?php if (isset($_SESSION['error'])): ?>
        <?php showError($_SESSION['error']); unset($_SESSION['error']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <?php showSuccess($_SESSION['success']); unset($_SESSION['success']); ?>
      <?php endif; ?>

      <form method="POST" action="signup_process.php">
        <div class="input-group">
          <input type="text" placeholder="Full Name" name="name" required />
        </div>
        <div class="input-group">
          <input type="text" placeholder="Username" name="username" required/>
        </div>
        <div class="input-group">
          <input type="email" placeholder="Email Address" name="email" required />
        </div>
        <div class="input-group">
          <input type="password" placeholder="Password" name="password" required />
        </div>
        <div class="input-group">
          <input type="password" placeholder="Confirm Password" name="confirm_password" required />
        </div>
        <button type="submit"><i class="fas fa-user-plus"></i> Create Account</button>
      </form>
      <a href="../admin/login.php" class="auth-link">Already have an account? Login</a>
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>
</body>

</html>