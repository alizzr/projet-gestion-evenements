<?php
// Fichier: services/notification-service/src/index.php
header('Content-Type: application/json');

// Simulation d'envoi d'email (Logique Mailgun/SES)
$input = json_decode(file_get_contents('php://input'), true);

echo json_encode([
    'status' => 'sent',
    'service' => 'Notification Microservice',
    'provider' => 'SMTP Mock',
    'timestamp' => date('Y-m-d H:i:s'),
    'recipient' => $input['email'] ?? 'unknown@example.com'
]);
?>