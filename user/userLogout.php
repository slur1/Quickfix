<?php
session_start(); // Start the session

// Destroy the session and log the user out
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session
header('Location: userLogin.php'); // Redirect to login page
exit();
