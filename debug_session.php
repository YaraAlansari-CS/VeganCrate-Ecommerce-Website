<?php
// Database Configuration
$host = "localhost";  // Change if using a remote database
$dbname = "vegancrate_db";  // Your database name
$username = "root";  // Default XAMPP username
$password = "";  // Default XAMPP password (leave empty)

// Establish database connection using PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
