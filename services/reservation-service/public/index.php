<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'service' => 'Reservation Service',
    'version' => '1.0',
    'message' => 'Service de réservation (PHP Natif) opérationnel.'
]);
?>