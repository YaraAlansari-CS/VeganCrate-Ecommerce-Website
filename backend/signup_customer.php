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
        !isset($data['password'])
    ) {
        echo json_encode(['error' => 'Missing required fields.']);
        exit;
    }

    // Retrieve and sanitize input values
    $full_name = trim($data['full_name']);
    $email = trim($data['email']);
    $password = $data['password'];
    $role = "customer"; // Set the role as customer

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

    // Insert into the customers table
    $customerQuery = "INSERT INTO customers (user_id) VALUES (:user_id)";
    $customerStmt = $conn->prepare($customerQuery);
    $customerStmt->execute([
        ':user_id' => $userId
    ]);

    echo json_encode(['success' => 'Customer registered successfully']);
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
