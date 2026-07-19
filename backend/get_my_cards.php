<?php
include 'config.php'; // Database connection using PDO
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = intval($_SESSION['user_id']);

try {
    // Step 1: Get customer_id from the customers table
    $query = "SELECT customer_id FROM customers WHERE user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        echo json_encode(["error" => "Customer not found"]);
        exit;
    }

    $customer_id = $customer['customer_id'];

    // Step 2: Fetch the customer's saved cards
    $query = "SELECT card_id, card_number, expiry_date, cvv, cardholder_name 
              FROM cards 
              WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the card details by only showing the last 4 digits of the card number
    $formattedCards = [];
    foreach ($cards as $card) {
        $formattedCards[] = [
            "card_id"     => $card["card_id"],
            "card_number" => substr($card["card_number"], -4), // Show only last 4 digits
            "expiry_date" => $card["expiry_date"],
        ];
    }

    // Return JSON response
    if (empty($formattedCards)) {
        echo json_encode(["message" => "No saved cards found."]);
    } else {
        echo json_encode($formattedCards);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Failed to fetch cards: " . $e->getMessage()]);
}
?>
