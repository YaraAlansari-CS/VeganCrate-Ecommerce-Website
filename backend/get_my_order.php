<?php
session_start();
include 'config.php'; // This file sets up the PDO connection in $conn

header('Content-Type: application/json');

// Only accept GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["error" => "order_id not provided"]);
    exit;
}

$orderId = $_GET['id'];

try {
    // 1. Get order details from the orders table
    $stmt = $conn->prepare("SELECT total_price, status, created_at, payment_method, shipping_company_id FROM orders WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(["error" => "Order not found"]);
        exit;
    }

    // 2. Get order items along with product details by joining order_items with products table
    $stmt = $conn->prepare("SELECT 
                                oi.quantity, 
                                oi.price as price, 
                                p.name, 
                                p.image_url 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.product_id 
                            WHERE oi.order_id = ?");
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Get shipping company details using shipping_company_id from the order
    $stmt = $conn->prepare("SELECT 
                                name AS shipping_company_name, 
                                contact AS shipping_contact, 
                                tracking_url AS shipping_tracking_url, 
                                shipping_price 
                            FROM shipping_companies 
                            WHERE company_id = ?");
    $stmt->execute([$order['shipping_company_id']]);
    $shipping = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Combine the order details, order items, and shipping details into a single response
    $response = [
        "total_price" => $order['total_price'],
        "status" => $order['status'],
        "created_at" => $order['created_at'],
        "payment_method" => $order['payment_method'],
        "items" => $items, // each item includes: name, price (each), quantity, image_url
        "shipping_company_name" => $shipping ? $shipping['shipping_company_name'] : null,
        "shipping_price" => $shipping ? $shipping['shipping_price'] : null,
        "shipping_contact" => $shipping ? $shipping['shipping_contact'] : null,
        "shipping_tracking_url" => $shipping ? $shipping['shipping_tracking_url'] : null
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error fetching order details: " . $e->getMessage()]);
}
?>
