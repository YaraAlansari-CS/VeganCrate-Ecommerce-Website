<?php
include 'config.php'; // Ensure this file sets up your PDO connection as $conn

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the JSON payload from the request body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Check if JSON decoding succeeded
    if (!$data) {
        echo json_encode(["error" => "Invalid JSON data."]);
        exit();
    }

    // Validate required fields
    if (
        !isset($data['company_id']) ||
        !isset($data['name']) ||
        !isset($data['contact']) ||
        !isset($data['tracking_url']) ||
        !isset($data['shipping_price'])
    ) {
        echo json_encode(["error" => "Missing required fields."]);
        exit();
    }

    // Retrieve and sanitize input values
    $company_id = $data['company_id'];
    $name = trim($data['name']);
    $contact = trim($data['contact']);
    $tracking_url = trim($data['tracking_url']);
    $shipping_price = $data['shipping_price'];

    try {
        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE shipping_companies 
                                SET name = ?, contact = ?, tracking_url = ?, shipping_price = ?
                                WHERE company_id = ?");
        $stmt->execute([$name, $contact, $tracking_url, $shipping_price, $company_id]);

        // Check if a row was updated
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => "Shipping company updated successfully."]);
        } else {
            echo json_encode(["error" => "No changes made or shipping company not found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error updating shipping company: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}
?>
