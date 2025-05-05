<?php
// Start session
session_start();
require_once '../includes/db.php';
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
      <i class="fas fa-user-plus" aria-hidden="true"></i>
    </div>
    <h1 class="title">Create an Account</h1>

    <div class="signup-form">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message" role="alert">
          <i class="fas fa-exclamation-circle" aria-hidden="true"></i> <?php echo $_SESSION['error'];
                                                    unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="message-box success" role="alert">
          <i class="fas fa-check-circle" aria-hidden="true"></i> <?php echo $_SESSION['success'];
                                              unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="signup_process.php">
        <div class="input-group">
          <input type="text" placeholder="Full Name" name="name" aria-label="Full name" required />
        </div>
        <div class="input-group">
          <input type="text" placeholder="Username" name="username" aria-label="Username" required/>
        </div>
        <div class="input-group">
          <input type="email" placeholder="Email Address" name="email" aria-label="Email address" required />
        </div>
        <div class="input-group">
          <input type="password" placeholder="Password" name="password" aria-label="Password" required />
        </div>
        <div class="input-group">
          <input type="password" placeholder="Confirm Password" name="confirm_password" aria-label="Confirm password" required />
        </div>
        <button type="submit" aria-label="Submit"><i class="fas fa-user-plus" aria-hidden="true"></i> Create Account</button>
      </form>
      <a href="../admin/login.php" class="auth-link">Already have an account? Login</a>
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>
  <script src="../js/validation.js"></script>
</body>

</html>