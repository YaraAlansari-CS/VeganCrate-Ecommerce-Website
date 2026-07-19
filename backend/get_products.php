<?php
include 'config.php'; // Database connection

header('Content-Type: application/json');

try {
    // Query to fetch all products
    $query = "SELECT * FROM products";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($products) {
        echo json_encode($products);
    } else {
        echo json_encode(["message" => "No products to display"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Failed to fetch products: " . $e->getMessage()]);
}
?>