<?php
$username = "kavatafaith412@gmail.com";
$apiKey   = "atsk_69d75902c4b635a1ddf1ab5c7342c07dddc4f5791f8ce05b559ccae99b9481739d6190d1";

$recipients = "+254769586256"; 
$message    = "Hello, this is an update from Chama Management System.";

$url = "https://api.africastalking.com/version1/messaging";
$data = array(
    'username' => $username,
    'to'       => $recipients,
    'message'  => $message
);

$headers = array('apiKey: ' . $apiKey);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
