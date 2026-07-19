<?php
include 'config.php'; // Database connection

header('Content-Type: application/json');

try {
    // Query to fetch all shipping companies including the company_id
    $query = "SELECT company_id, name, contact, tracking_url, shipping_price FROM shipping_companies";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $shipping_companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($shipping_companies) {
        echo json_encode($shipping_companies);
    } else {
        echo json_encode(["message" => "No shipping companies available"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Failed to fetch shipping companies: " . $e->getMessage()]);
}
?>

