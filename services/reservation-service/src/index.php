<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Routeur basique
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    // Simulation de paiement
    $eventId = $input['event_id'] ?? 0;
    
    // Simulation d'appel API Stripe
    // ... traitement ...
    $success = true;

    if ($success) {
        echo json_encode([
            "status" => "CONFIRMED",
            "message" => "Paiement Stripe validé",
            "transaction_id" => "txn_" . uniqid(),
            "event_id" => $eventId
        ]);
    } else {
        http_response_code(400);
        echo json_encode(["status" => "FAILED", "message" => "Paiement refusé"]);
    }
} else {
    echo json_encode(["message" => "Service Réservation (PHP Natif) en ligne"]);
}
?>