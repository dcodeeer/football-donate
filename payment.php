<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $team = $_POST['team'];
  $amount = $_POST['amount'];
  $name = $_POST['name'];
  $message = $_POST['message'];

  if (isset($team) && isset($amount) && isset($name) && isset($message)) {
    $url = 'https://api.yookassa.ru/v3/payments';

    $data = array(
      'amount' => array(
        'value' => $amount . '.00',
        'currency' => 'RUB'
      ),
      'capture' => true,
      'confirmation' => array(
        'type' => 'redirect',
        'return_url' => 'https://lovemyteam.ru',
      ),
      'description' => 'Оплата',
      'metadata' => array(
        'name'    => $name,
        'message' => $message,
        'team_id' => $team,
      ),
    );
    $json_data = json_encode($data);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Idempotence-Key: ' . uniqid(),
        'Content-Length: ' . strlen($json_data),
        'Authorization: Basic ' . base64_encode('365194:test_HyzzrDkEFNm_V9n98YxfJJFRrW96LuYaUWK9n0_QefA')
    ));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if($response === false) {
        echo 'Ошибка cURL: ' . curl_error($ch);
    }
    curl_close($ch);

    $json = json_decode($response, true);
    header('Location: ' . $json['confirmation']['confirmation_url']);
  }
}