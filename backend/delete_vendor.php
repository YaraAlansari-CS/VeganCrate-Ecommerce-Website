<?php
include 'config.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $vendorId = $_GET['id'];
        
        try {
            // Prepare the delete statement for the vendors table
            $stmt = $conn->prepare("DELETE FROM vendors WHERE vendor_id = ?");
            $stmt->execute([$vendorId]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "Vendor deleted successfully"]);
            } else {
                echo json_encode(["error" => "Vendor not found or already deleted"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error deleting vendor: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "No vendor ID provided"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
