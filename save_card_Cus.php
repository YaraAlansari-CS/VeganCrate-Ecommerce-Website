<?php
include 'config.php'; // Database connection
session_start();

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get customer_id from customers table
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

    // Decode the input JSON from the request body
    $input = json_decode(file_get_contents('php://input'), true);

    if (
        empty($input['street']) ||
        empty($input['city']) ||
        empty($input['country']) ||
        empty($input['zip_code'])
    ) {
        echo json_encode(["error" => "All fields are required."]);
        exit;
    }

    $street = $input['street'];
    $city = $input['city'];
    $country = $input['country'];
    $zip_code = $input['zip_code'];

    // Insert the new address into the addresses table
    $query = "
        INSERT INTO addresses (customer_id, street, city, country, zip_code)
        VALUES (:customer_id, :street, :city, :country, :zip_code)
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->bindParam(':street', $street, PDO::PARAM_STR);
    $stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $stmt->bindParam(':zip_code', $zip_code, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Address added successfully."]);
} catch (Exception $e) {
    echo json_encode(["error" => "Failed to add address: " . $e->getMessage()]);
}
