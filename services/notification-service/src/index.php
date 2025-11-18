<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    $email = $input['email'] ?? 'unknown@test.com';
    $msg = $input['message'] ?? 'Confirmation';

    // Simulation d'envoi (Mailgun/SES)
    // file_put_contents('logs.txt', "Email sent to $email\n", FILE_APPEND);

    echo json_encode([
        "status" => "SENT",
        "provider" => "Amazon SES (Simulation)",
        "recipient" => $email,
        "timestamp" => date('c')
    ]);
} else {
    echo json_encode(["message" => "Service Notification (PHP Natif) en ligne"]);
}
?>