<?php
include 'config.php'; // Database connection

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['vendor_id']) || empty($_POST['description']) || empty($_POST['price']) || empty($_POST['stock']) || empty($_POST['category'])) {
            echo json_encode(["error" => "All fields are required"]);
            exit();
        }

        $name = trim($_POST['name']);
        $vendor_id = $_POST['vendor_id'];
        $description = trim($_POST['description']);
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = trim($_POST['category']);
        $image_url = '';

        // Handle Image Upload (Allow using existing assets/images/)
        if (!empty($_FILES['image_url']['name'])) {
            // This directory is used for moving the file.
            $target_dir = "../assets/uploads/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
            }

            $image_name = basename($_FILES["image_url"]["name"]);
            $target_file = $target_dir . $image_name;

            // Move the uploaded file to the uploads folder
            if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)) {
                // Store the image path without the "../" prefix
                $image_url = "assets/uploads/" . $image_name;
            } else {
                echo json_encode(["error" => "Failed to upload image."]);
                exit();
            }
        } else {
            // If no image uploaded, allow selecting from assets/images/
            if (!empty($_POST['image_path'])) {
                $image_url = "assets/images/" . basename($_POST['image_path']);
            }
        }

        // Insert product into database
        $query = "INSERT INTO products (vendor_id, name, description, price, stock, category, image_url) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$vendor_id, $name, $description, $price, $stock, $category, $image_url]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => "Product added successfully"]);
        } else {
            echo json_encode(["error" => "Database insert failed"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to add product: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>

