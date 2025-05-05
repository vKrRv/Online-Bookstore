<?php
session_start();

// Unset variables
session_unset();

// Destroy session
session_destroy();

// Redirect to home 
header("Location: ../index.php");
exit();
?>