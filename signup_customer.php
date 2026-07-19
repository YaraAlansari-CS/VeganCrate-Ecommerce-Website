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
        !isset($data['vendor_id']) ||
        !isset($data['user_id']) ||
        !isset($data['name']) ||
        !isset($data['email']) ||
        !isset($data['password']) ||
        !isset($data['role']) ||
        !isset($data['business_name']) ||
        !isset($data['business_description']) ||
        !isset($data['rating'])
    ) {
        echo json_encode(["error" => "Missing required fields."]);
        exit();
    }

    // Retrieve and sanitize input values
    $vendor_id = $data['vendor_id'];
    $user_id = $data['user_id'];
    $name = trim($data['name']);
    $email = trim($data['email']);
    $password = $data['password'];
    $role = trim($data['role']);
    $business_name = trim($data['business_name']);
    $business_description = trim($data['business_description']);
    $rating = $data['rating'];

    // Hash the password using password_hash (this encrypts the password)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Update the users table with the provided fields
        $stmt1 = $conn->prepare("UPDATE users 
                                 SET name = ?, email = ?, password = ?, role = ?
                                 WHERE user_id = ?");
        $stmt1->execute([$name, $email, $hashed_password, $role, $user_id]);

        // Update the vendors table with the vendor-specific fields
        $stmt2 = $conn->prepare("UPDATE vendors 
                                 SET business_name = ?, business_description = ?, rating = ?
                                 WHERE vendor_id = ?");
        $stmt2->execute([$business_name, $business_description, $rating, $vendor_id]);

        // Check if either update affected any rows
        if ($stmt1->rowCount() > 0 || $stmt2->rowCount() > 0) {
            echo json_encode(["success" => "Vendor updated successfully."]);
        } else {
            echo json_encode(["error" => "No changes made or vendor not found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error updating vendor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}
?>
