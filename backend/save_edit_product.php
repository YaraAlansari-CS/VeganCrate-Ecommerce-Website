<?php
include 'config.php'; // Ensure this file sets up the $conn PDO connection

header('Content-Type: application/json');

// This API only accepts POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the JSON payload from the request body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Check if JSON decoding succeeded
    if (!$data) {
        echo json_encode(['error' => 'Invalid JSON data.']);
        exit();
    }

    // Validate required fields
    if (
        !isset($data['product_id']) ||
        !isset($data['vendor_id']) ||
        !isset($data['name']) ||
        !isset($data['description']) ||
        !isset($data['price']) ||
        !isset($data['stock']) ||
        !isset($data['category'])
    ) {
        echo json_encode(['error' => 'Missing required fields.']);
        exit();
    }

    // Retrieve and sanitize input values
    $product_id  = $data['product_id'];
    $vendor_id   = $data['vendor_id'];
    $name        = trim($data['name']);
    $description = trim($data['description']);
    $price       = $data['price'];
    $stock       = $data['stock'];
    $category    = trim($data['category']);
    
    // Determine if image_url is provided. If it is null, don't update the image_url column.
    $image_url = array_key_exists('image_url', $data) ? $data['image_url'] : null;

    try {
        if ($image_url === null) {
            // Update query without updating the image_url column
            $query = "UPDATE products 
                      SET vendor_id = ?, name = ?, description = ?, price = ?, stock = ?, category = ? 
                      WHERE product_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$vendor_id, $name, $description, $price, $stock, $category, $product_id]);
        } else {
            // Update query including image_url column
            $query = "UPDATE products 
                      SET vendor_id = ?, name = ?, description = ?, price = ?, stock = ?, category = ?, image_url = ? 
                      WHERE product_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$vendor_id, $name, $description, $price, $stock, $category, $image_url, $product_id]);
        }

        // Check if any row was updated
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => 'Product updated successfully.']);
        } else {
            echo json_encode(['error' => 'No changes made or product not found.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to update product: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
