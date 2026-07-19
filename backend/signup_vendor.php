<?php
include 'config.php'; // Database connection

header('Content-Type: application/json');

try {
    // Decode the JSON payload from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Check that all required fields are provided
    if (
        !isset($data['full_name']) ||
        !isset($data['email']) ||
        !isset($data['password']) ||
        !isset($data['business_name']) ||
        !isset($data['business_description'])
    ) {
        echo json_encode(['error' => 'Missing required fields.']);
        exit;
    }

    // Retrieve and sanitize input values
    $full_name = trim($data['full_name']);
    $email = trim($data['email']);
    $password = $data['password'];
    $business_name = trim($data['business_name']);
    $business_description = trim($data['business_description']);
    $role = "vendor"; // Set the role as vendor

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $emailCheckQuery = "SELECT COUNT(*) FROM users WHERE email = :email";
    $emailCheckStmt = $conn->prepare($emailCheckQuery);
    $emailCheckStmt->execute([':email' => $email]);
    $emailExists = $emailCheckStmt->fetchColumn();

    if ($emailExists) {
        echo json_encode(['error' => 'This email is already registered.']);
        exit;
    }

    // Insert into the users table
    $userQuery = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->execute([
        ':name' => $full_name,
        ':email' => $email,
        ':password' => $hashed_password,
        ':role' => $role
    ]);

    // Retrieve the newly generated user ID (assuming user_id is auto-increment)
    $userId = $conn->lastInsertId();

    // Insert into the vendors table
    // Note: vendor_id is assumed to be auto-increment in the vendors table.
    $vendorQuery = "INSERT INTO vendors (user_id, business_name, business_description, rating) VALUES (:user_id, :business_name, :business_description, :rating)";
    $vendorStmt = $conn->prepare($vendorQuery);
    $vendorStmt->execute([
        ':user_id' => $userId,
        ':business_name' => $business_name,
        ':business_description' => $business_description,
        ':rating' => 0.00
    ]);

    echo json_encode(['success' => 'Vendor registered successfully']);
} catch (PDOException $e) {
    // Handle duplicate email or other database errors
    if ($e->getCode() === '23000') {
        echo json_encode(['error' => 'This email is already registered.']);
    } else {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
}
?>
