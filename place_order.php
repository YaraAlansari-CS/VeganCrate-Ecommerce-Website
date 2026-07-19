<?php
session_start(); // Start the session

// Destroy the session completely
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the welcome page
header("Location: ../frontend/index.php"); 
exit(); // Ensure the script stops here
?>
