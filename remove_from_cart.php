<?php
session_start();

include 'config.php';  

header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}

// Get the JSON POST body
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input.']);
    exit;
}

// Validate required fields
if (!isset($data['total_price'], $data['shipping_company_id'], $data['payment_method'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

// Cast input values
$total_price         = floatval($data['total_price']);
$shipping_company_id = intval($data['shipping_company_id']);
$payment_method_input = $data['payment_method'];

// Convert payment method to proper format: "Card" or "Cash"
$payment_method = (strtolower($payment_method_input) === 'card') ? 'Card' : 'Cash';

// If payment method is "Card", ensure a card_id is provided
$card_id = null;
if ($payment_method === 'Card') {
    if (isset($data['card_id']) && !empty($data['card_id'])) {
        $card_id = intval($data['card_id']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing card information for card payment method.']);
        exit;
    }
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: user not logged in.']);
    exit;
}
$user_id = $_SESSION['user_id'];

try {
    // Retrieve the customer_id from the customers table
    $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$customer) {
        http_response_code(404);
        echo json_encode(['error' => 'Customer not found.']);
        exit;
    }
    $customer_id = $customer['customer_id'];

    // Ensure that there is at least one product in the cart
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Cart is empty.']);
        exit;
    }

    // Begin transaction
    $conn->beginTransaction();

    // Insert a new order into the orders table
    $orderSql = "INSERT INTO orders (customer_id, total_price, status, shipping_company_id, payment_method, card_id)
                 VALUES (:customer_id, :total_price, :status, :shipping_company_id, :payment_method, :card_id)";
    $stmtOrder = $conn->prepare($orderSql);
    
    $status = 'pending';
    $stmtOrder->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmtOrder->bindParam(':total_price', $total_price);
    $stmtOrder->bindParam(':status', $status);
    $stmtOrder->bindParam(':shipping_company_id', $shipping_company_id, PDO::PARAM_INT);
    $stmtOrder->bindParam(':payment_method', $payment_method);
    
    if ($card_id === null) {
        $stmtOrder->bindValue(':card_id', null, PDO::PARAM_NULL);
    } else {
        $stmtOrder->bindParam(':card_id', $card_id, PDO::PARAM_INT);
    }
    
    $stmtOrder->execute();

    // Get the auto-generated order_id
    $order_id = $conn->lastInsertId();

    // Prepare the statement for inserting order items (now includes vendor_id)
    $orderItemSql = "INSERT INTO order_items (order_id, product_id, quantity, price, vendor_id)
                     VALUES (:order_id, :product_id, :quantity, :price, :vendor_id)";
    $stmtItem = $conn->prepare($orderItemSql);

    // Loop through the cart items stored in the session and insert each as an order item
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $quantity = $product['quantity'];
        $price    = $product['price'];

        // Retrieve vendor_id for this product
        $vendorStmt = $conn->prepare("SELECT vendor_id FROM products WHERE product_id = :product_id");
        $vendorStmt->execute(['product_id' => $product_id]);
        $vendor = $vendorStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$vendor) {
            throw new Exception("Product ID $product_id not found in products table.");
        }
        
        $vendor_id = $vendor['vendor_id'];

        // Insert into order_items table
        $stmtItem->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmtItem->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmtItem->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmtItem->bindParam(':price', $price);
        $stmtItem->bindParam(':vendor_id', $vendor_id, PDO::PARAM_INT);
        $stmtItem->execute();
    }

    // Commit the transaction
    $conn->commit();

    // Clear the cart after the order has been placed
    unset($_SESSION['cart']);

    // Set success message in the session
    $_SESSION['order_success_message'] = "Your order has been placed successfully.";

    // Return JSON response with order_id and success message
    echo json_encode([
        'success'   => true,
        'message'   => "Your order has been placed successfully.",
        'order_id'  => $order_id
    ]);
    exit;
} catch (Exception $e) {
    // Roll back the transaction if an error occurs
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
    exit;
}

