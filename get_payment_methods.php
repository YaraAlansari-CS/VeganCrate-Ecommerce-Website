<?php
session_start();
include 'config.php'; // This file should set up the PDO connection in $conn

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the user_id is set in the session
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        try {
            // Get the customer_id from the customers table using the user_id
            $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE user_id = ?");
            $stmt->execute([$userId]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                $customerId = $customer['customer_id'];

                // Fetch all orders for the customer_id from the orders table
                $stmt = $conn->prepare("SELECT order_id, created_at FROM orders WHERE customer_id = ?");
                $stmt->execute([$customerId]);
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Return the orders as a JSON response.
                // If no orders exist, this will return an empty array.
                echo json_encode($orders);
            } else {
                echo json_encode(["error" => "Customer not found for the provided user id"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching orders: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "User not logged in or user id not provided in session"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
