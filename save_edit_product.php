<?php
// Enable error reporting (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php'; // Database connection settings

header('Content-Type: application/json');

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request"]);
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Please log in first"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get customer_id from the customers table for the logged in user
$query = "SELECT customer_id FROM customers WHERE user_id = ?";
$stmt  = $conn->prepare($query);
$stmt->execute([$user_id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo json_encode(["error" => "Customer not found"]);
    exit();
}

$customer_id = $customer['customer_id'];

// Decode the JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (
    empty($input['card_number']) ||
    empty($input['expiry_date']) ||
    empty($input['cvv']) ||
    empty($input['cardholder_name'])
) {
    echo json_encode(["error" => "All fields are required."]);
    exit();
}

$card_number    = trim($input['card_number']);
$expiry_date    = trim($input['expiry_date']); // Expected format: MM/YY
$cvv            = trim($input['cvv']);
$cardholderName = trim($input['cardholder_name']);

// For troubleshooting: store card details directly without encryption
// WARNING: Do NOT use this approach in production. Storing sensitive data in plain text is insecure.

// Insert the new card details into the cards table
$query = "INSERT INTO cards (customer_id, card_number, expiry_date, cvv, cardholder_name)
          VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if ($stmt->execute([$customer_id, $card_number, $expiry_date, $cvv, $cardholderName])) {
    echo json_encode(["success" => true, "message" => "Card added successfully."]);
    exit();
} else {
    // Log the detailed PDO error info for debugging
    error_log("DB Insert Error: " . print_r($stmt->errorInfo(), true));
    echo json_encode(["error" => "Failed to add card"]);
    exit();
}
?>
