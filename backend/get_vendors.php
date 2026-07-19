<?php
include 'config.php'; // Database connection

header('Content-Type: application/json');

try {
    // Join the vendors and users tables to retrieve the required fields
    $query = "SELECT 
                  u.user_id, 
                  u.name, 
                  u.email, 
                  u.password, 
                  u.role, 
                  v.vendor_id, 
                  v.business_name, 
                  v.business_description, 
                  v.rating
              FROM vendors v
              JOIN users u ON v.user_id = u.user_id";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($vendors) {
        echo json_encode($vendors);
    } else {
        echo json_encode(["message" => "No vendors to display"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Failed to fetch vendors: " . $e->getMessage()]);
}
?>
