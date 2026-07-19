<?php
include '../backend/config.php'; // Database connection

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $product_id = $_POST['product_id'];
        $name = trim($_POST['name']);
        $vendor_id = $_POST['vendor_id'];
        $description = trim($_POST['description']);
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = trim($_POST['category']);

        // Check if product exists
        $checkQuery = "SELECT * FROM products WHERE product_id = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$product_id]);
        $product = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode(["error" => "Product not found"]);
            exit();
        }

        // Handle Image Upload (Optional)
        $image_url = $product['image_url']; // Keep the existing image if no new image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $target_dir = "../uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $image_url = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image_url);
        }

        // Update Query
        $query = "UPDATE products SET vendor_id = ?, name = ?, description = ?, price = ?, stock = ?, category = ?, image_url = ? WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$vendor_id, $name, $description, $price, $stock, $category, $image_url, $product_id]);

        echo json_encode(["success" => "Product updated successfully"]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to update product: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
