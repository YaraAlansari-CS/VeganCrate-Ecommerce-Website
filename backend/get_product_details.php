<?php
include 'config.php'; // Ensure this file sets up the $conn variable for the database connection

header('Content-Type: application/json');

// Only allow GET requests for this endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the product id is provided
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $productId = $_GET['id'];

        try {
            // Prepare and execute the query to fetch the product details
            $stmt = $conn->prepare("SELECT product_id, vendor_id, name, description, price, stock, category, image_url FROM products WHERE product_id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                echo json_encode($product);
            } else {
                echo json_encode(["error" => "Product not found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching product details: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Product id not provided"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
