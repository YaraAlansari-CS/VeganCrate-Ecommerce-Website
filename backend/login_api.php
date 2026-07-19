<?php
session_start();
include 'config.php'; // Database connection

// Ensure the response is in JSON format
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
    exit();
}

// Read the JSON payload from the request body
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

if ($data === null) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON input"
    ]);
    exit();
}

// Extract and sanitize input data
$email = isset($data['email']) ? trim($data['email']) : '';
$password = isset($data['password']) ? trim($data['password']) : '';

// Validate required fields
if (empty($email) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required"
    ]);
    exit();
}

// Query the database for the user using a prepared statement
$query = "SELECT user_id, name, email, password, role FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    // Set secure cookies (valid for 30 days)
    setcookie("user_id", $user['user_id'], time() + (86400 * 30), "/", "", true, true);
    setcookie("name", $user['name'], time() + (86400 * 30), "/", "", true, true);
    setcookie("email", $user['email'], time() + (86400 * 30), "/", "", true, true);
    setcookie("role", $user['role'], time() + (86400 * 30), "/", "", true, true);

    // Return a JSON response indicating successful login along with the user's role
    echo json_encode([
        "success" => true,
        "message" => "Login successful",
        "role" => $user['role']
    ]);
    exit();
} else {
    // Login failed; return error message as JSON
    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password"
    ]);
    exit();
}
?>
