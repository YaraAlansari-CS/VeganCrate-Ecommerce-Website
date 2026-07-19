<?php
session_start(); // Start session to access session variables
include 'config.php'; // Database connection

header('Content-Type: application/json');

try {
    // Check if the vendor is logged in by verifying that user_id is in session
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["error" => "User not logged in."]);
        exit();
    }
    
    $userId = $_SESSION['user_id'];
    
    // Retrieve vendor_id from the vendors table using the user_id
    $vendorQuery = "SELECT vendor_id FROM vendors WHERE user_id = ?";
    $vendorStmt = $conn->prepare($vendorQuery);
    $vendorStmt->execute([$userId]);
    $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$vendor) {
        echo json_encode(["error" => "Vendor not found for this user."]);
        exit();
    }
    
    $vendorId = $vendor['vendor_id'];
    
    // Query the order_items table to get all distinct order_ids for this vendor
    $orderItemsQuery = "SELECT DISTINCT order_id FROM order_items WHERE vendor_id = ?";
    $orderItemsStmt = $conn->prepare($orderItemsQuery);
    $orderItemsStmt->execute([$vendorId]);
    $orderIds = $orderItemsStmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($orderIds)) {
        echo json_encode(["message" => "No orders to display"]);
        exit();
    }
    
    // Build a list of placeholders for the IN clause based on the number of order IDs
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    
    // Query the orders table for orders with these order_ids
    $ordersQuery = "SELECT order_id, customer_id, total_price, status, shipping_company_id, created_at, payment_method, card_id
                    FROM orders
                    WHERE order_id IN ($placeholders)";
    $ordersStmt = $conn->prepare($ordersQuery);
    $ordersStmt->execute($orderIds);
    $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

    if ($orders) {
        echo json_encode($orders);
    } else {
        echo json_encode(["message" => "No orders to display"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Failed to fetch orders: " . $e->getMessage()]);
}
?>
