<?php
include 'config.php'; // Optional: Include if you need a database connection

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Hard-coded plaintext
    $plaintext = "123456";
    
    // Encrypt (hash) the password using the default algorithm (with an automatically generated salt)
    $hashed = password_hash($plaintext, PASSWORD_DEFAULT);
    
    // Return the hashed password in JSON format
    echo json_encode([
        "success"   => "Password encrypted successfully",
        "encrypted" => $hashed
    ]);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
