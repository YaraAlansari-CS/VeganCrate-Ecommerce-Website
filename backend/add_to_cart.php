<?php
session_start();
include 'config.php'; // Database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'You must be logged in to add items to the cart.']);
        exit;
    }

    if (!isset($data['product_id'], $data['quantity'])) {
        echo json_encode(['error' => 'Invalid request. Product ID and quantity are required.']);
        exit;
    }

    $productId = intval($data['product_id']);
    $quantity = intval($data['quantity']);

    if ($quantity <= 0) {
        echo json_encode(['error' => 'Invalid quantity.']);
        exit;
    }

    // Fetch product details from database
    $query = "SELECT product_id, name, price, image_url, category FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['error' => 'Product not found.']);
        exit;
    }

    // Initialize cart session if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Store full product details in the session
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'product_id' => $product['product_id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image_url' => $product['image_url'],
            'category' => $product['category'],
            'quantity' => $quantity
        ];
    }

    echo json_encode([
        'success' => 'Product added to cart successfully.',
        'cart' => $_SESSION['cart']
    ]);
}

