<?php
include 'config.php'; // Database connection

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['contact']) || empty($_POST['tracking_url']) || empty($_POST['shipping_price'])) {
            echo json_encode(["error" => "All fields are required."]);
            exit();
        }

        // Collect data
        $name = trim($_POST['name']);
        $contact = trim($_POST['contact']);
        $tracking_url = trim($_POST['tracking_url']);
        $shipping_price = $_POST['shipping_price'];

        // Insert into database
        $query = "INSERT INTO shipping_companies (name, contact, tracking_url, shipping_price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$name, $contact, $tracking_url, $shipping_price]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => "Shipping company added successfully."]);
        } else {
            echo json_encode(["error" => "Failed to add shipping company."]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}
?>
