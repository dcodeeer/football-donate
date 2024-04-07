<?php

require_once 'db.php';
$lib = new Database();
$db = $lib->getConnection();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  http_response_code(404);
  exit();
}

$payload = file_get_contents('php://input');

$data = json_decode($payload, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    exit('Ошибка при декодировании JSON');
}

if ($data['type'] != 'notification' || $data['event'] != 'payment.succeeded') {
  http_response_code(400);
  exit('Ошибка при декодировании JSON');
}

$amount  = $data['object']['amount']['value'];
$name    = $data['object']['metadata']['name'];
$message = $data['object']['metadata']['message'];
$team_id = $data['object']['metadata']['team_id'];

$stmt = $db->prepare('INSERT INTO donations (amount, name, message, team_id) VALUES (:amount, :name, :message, :team_id)');
$stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':message', $message, PDO::PARAM_STR);
$stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
$stmt->execute();


http_response_code(200);