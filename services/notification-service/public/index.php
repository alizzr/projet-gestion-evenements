<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'service' => 'Notification Service',
    'message' => 'Service de notification prêt à envoyer des emails.'
]);
?>