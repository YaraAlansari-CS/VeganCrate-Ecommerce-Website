<?php
include 'config.php'; // Database connection

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate required fields (now including 'role')
        if (
            empty($_POST['vendor_id']) || 
            empty($_POST['business_name']) || 
            empty($_POST['business_description']) || 
            empty($_POST['rating']) || 
            empty($_POST['name']) || 
            empty($_POST['email']) || 
            empty($_POST['password']) ||
            empty($_POST['role'])
        ) {
            echo json_encode(["error" => "All fields are required"]);
            exit();
        }

        // Retrieve and sanitize vendor-specific fields
        $vendor_id = $_POST['vendor_id'];
        $business_name = trim($_POST['business_name']);
        $business_description = trim($_POST['business_description']);
        $rating = $_POST['rating'];

        // Retrieve and sanitize user-specific fields
        $user_name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = trim($_POST['role']); // Accept the role from the input

        // Hash the password using password_hash (this encrypts the password)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the users table with the provided role
        $userQuery = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->execute([$user_name, $email, $hashed_password, $role]);

        if ($userStmt->rowCount() <= 0) {
            echo json_encode(["error" => "Failed to add user"]);
            exit();
        }

        // Get the new user's ID (assumes user_id is auto-increment)
        $newUserId = $conn->lastInsertId();

        // Insert vendor details into the vendors table using the new user_id
        $vendorQuery = "INSERT INTO vendors (vendor_id, user_id, business_name, business_description, rating) 
                        VALUES (?, ?, ?, ?, ?)";
        $vendorStmt = $conn->prepare($vendorQuery);
        $vendorStmt->execute([$vendor_id, $newUserId, $business_name, $business_description, $rating]);

        if ($vendorStmt->rowCount() > 0) {
            echo json_encode(["success" => "Vendor added successfully"]);
        } else {
            echo json_encode(["error" => "Database insert failed for vendor"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to add vendor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>

