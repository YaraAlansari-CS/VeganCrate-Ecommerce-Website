<?php
session_start(); // Start the session
header('Content-Type: text/plain'); // Display text output

// Check if session is empty
if (empty($_SESSION)) {
    echo "Session is empty.";
} else {
    echo "SESSION CONTENT:\n";
    print_r($_SESSION); // Print session contents in human-readable format
}
?>
