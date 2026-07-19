<?php
include '../backend/config.php'; // Database connection

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    try {
        $product_id = $_GET['id'];

        // Check if product exists
        $checkQuery = "SELECT * FROM products WHERE product_id = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$product_id]);
        $product = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode(["error" => "Product not found"]);
            exit();
        }

        // Delete product
        $query = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$product_id]);

        echo json_encode(["success" => "Product deleted successfully"]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to delete product: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Product ID is required"]);
}
?>
