<?php
include 'config.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $companyId = $_GET['id'];
        
        try {
            // Prepare the delete statement
            $stmt = $conn->prepare("DELETE FROM shipping_companies WHERE company_id = ?");
            $stmt->execute([$companyId]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "Shipping company deleted successfully"]);
            } else {
                echo json_encode(["error" => "Shipping company not found or already deleted"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error deleting shipping company: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "No shipping company ID provided"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
