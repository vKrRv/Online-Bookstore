<?php
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
  
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.querySelector("form");
      form.addEventListener("submit", function (e) {
        const name = form.name.value.trim();
        const username = form.username.value.trim();
        const email = form.email.value.trim();
        const password = form.password.value;
        const confirmPassword = form.confirm_password.value;

        let errors = [];
        let isValid = true;

        if (name === "") {
          errors.push("Full name is required.");
          isValid = false;
        }

        if (username === "") {
          errors.push("Username is required.");
          isValid = false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          errors.push("Please enter a valid email address.");
          isValid = false;
        }

        if (password.length < 6) {
          errors.push("Password must be at least 6 characters.");
          isValid = false;
        }

        if (password !== confirmPassword) {
          errors.push("Passwords do not match.");
          isValid = false;
        }

        const errorDiv = document.getElementById("client-error");
        errorDiv.innerHTML = "";

        if (!isValid) {
          e.preventDefault();
          errors.forEach(msg => {
            const p = document.createElement("p");
            p.className = "error-message";
            p.textContent = msg;
            errorDiv.appendChild(p);
          });
        }
      });
    });
  </script>
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

      <div id="client-error"></div>

      <form method="POST" action="signup_process.php">
        <div class="form-group">
          <label for="name">Full Name</label>
          <div class="input-group">
            <input type="text" name="name" id="name" placeholder="Full Name" required />
            <span class="hint">Enter your full legal name.</span>
          </div>
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-group">
            <input type="text" name="username" id="username" placeholder="Username" required />
            <span class="hint">Choose a unique username.</span>
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-group">
            <input type="email" name="email" id="email" placeholder="Email Address" required />
            <span class="hint">Use a valid email: user@example.com</span>
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" required />
            <span class="hint">Minimum 6 characters required.</span>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <div class="input-group">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
            <span class="hint">Must match the password above.</span>
          </div>
        </div>

        <button type="submit"><i class="fas fa-user-plus"></i> Create Account</button>
      </form>
      <a href="../admin/login.php" class="auth-link">Already have an account? Login</a>
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>
</body>

</html>
