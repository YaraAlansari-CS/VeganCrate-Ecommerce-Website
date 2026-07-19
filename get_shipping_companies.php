<?php
include 'config.php'; // This file should set up the PDO connection in $conn

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the company id is provided in the query string
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $companyId = $_GET['id'];

        try {
            // Prepare and execute the query to fetch shipping company details
            $stmt = $conn->prepare("SELECT company_id, name, contact, tracking_url, shipping_price FROM shipping_companies WHERE company_id = ?");
            $stmt->execute([$companyId]);
            $company = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($company) {
                echo json_encode($company);
            } else {
                echo json_encode(["error" => "Shipping company not found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching shipping company details: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Shipping company id not provided"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
