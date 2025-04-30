<?php
// Start  session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset session variables
session_unset();

// Destroy session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>