<?php
session_start();
require_once '../includes/db.php'; // your database connection file
require_once '../includes/functions.php'; // include functions file

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) { // check if admin exists
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin'] = true; // admin session variable
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("form");
            form.addEventListener("submit", function (e) {
                const username = form.username.value.trim();
                const password = form.password.value.trim();
                let isValid = true;
                let errorMessages = [];

                if (username === "") {
                    errorMessages.push("Username is required.");
                    isValid = false;
                }

                if (password === "") {
                    errorMessages.push("Password is required.");
                    isValid = false;
                }

                const errorDiv = document.getElementById("client-error");
                errorDiv.innerHTML = ""; // Clear previous errors

                if (!isValid) {
                    e.preventDefault();
                    errorMessages.forEach(msg => {
                        const p = document.createElement("p");
                        p.style.color = "red";
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

    <div class="login-container">
        <div class="auth-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h1 class="title">Admin Login</h1>

        <div class="login-form">
            <?php if (isset($error)): ?>
                <?php showError($error); ?>
            <?php endif; ?>

            <div id="client-error"></div> <!-- For client-side validation messages -->

            <form method="POST" action="login.php">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>
            <a href="../pages/signup.php" class="auth-link">Don't have an account? Sign up</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
