<?php
header('Content-Type: application/json');

// Load the XML file
$xml = simplexml_load_file('../data/payment_methods.xml');

// Check if the XML was loaded successfully
if (!$xml) {
    echo json_encode(["error" => "Failed to load payment methods."]);
    exit;
}

// Parse the XML into an array
$paymentMethods = [];
foreach ($xml->method as $method) {
    $paymentMethods[] = [
        "id" => (int)$method->id,
        "name" => (string)$method->name,
    ];
}

// Return the payment methods as JSON
echo json_encode($paymentMethods);
