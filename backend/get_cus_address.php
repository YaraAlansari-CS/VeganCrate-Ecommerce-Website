<?php
include 'config.php'; // Database connection
session_start();

header('Content-Type: application/json');

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

    // Get address details from addresses table
    $query = "SELECT street, city, country, zip_code FROM addresses WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $address = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($address) {
        echo json_encode($address);
    } else {
        echo json_encode(["error" => "No Address Found"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Failed to fetch address: " . $e->getMessage()]);
}
