<?php
include 'config.php'; // Ensure this file sets up the $conn variable for the database connection

header('Content-Type: application/json');

// Only allow GET requests for this endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the vendor id is provided
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $vendorId = $_GET['id'];

        try {
            // Prepare and execute the query to fetch the vendor details by joining vendors and users tables
            $stmt = $conn->prepare("SELECT 
                                        v.vendor_id, 
                                        u.user_id, 
                                        u.name, 
                                        u.email, 
                                        u.password, 
                                        u.role, 
                                        v.business_name, 
                                        v.business_description, 
                                        v.rating
                                    FROM vendors v
                                    JOIN users u ON v.user_id = u.user_id
                                    WHERE v.vendor_id = ?");
            $stmt->execute([$vendorId]);
            $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($vendor) {
                echo json_encode($vendor);
            } else {
                echo json_encode(["error" => "Vendor not found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching vendor details: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Vendor id not provided"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
