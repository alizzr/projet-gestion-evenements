<?php
// Fichier: services/reservation-service/src/index.php
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Simulation de traitement de paiement (Stripe Sandbox)
    $paymentStatus = 'success'; 
    $transactionId = 'txn_' . bin2hex(random_bytes(10));

    echo json_encode([
        'status' => 'success',
        'message' => 'Reservation confirmee',
        'payment_provider' => 'Stripe (Simulation)',
        'transaction_id' => $transactionId,
        'data' => [
            'event_id' => 123,
            'user_id' => 456,
            'seats' => 2
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'ready',
        'service' => 'Reservation Microservice (PHP Native)',
        'version' => '1.0'
    ]);
}
?>