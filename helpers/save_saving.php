<?php
// Connect to your database
require '../config/config.php'; 

// Get the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

$reference = $data['reference'];
$email = $data['email'];
$amount = $data['amount'];

// Verify payment with Paystack
$paystack_secret_key = 'sk_test_8f8413288a0652a3a1c0b023db7955eb1f79c6ca'; 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . rawurlencode($reference));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $paystack_secret_key",
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if ($result && $result['data']['status'] === 'success') {
    // Payment was successful, insert into your database
    $amount_saved = $result['data']['amount'] / 100; 

    $stmt = $conn->prepare("INSERT INTO savings (email, amount_saved, reference, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sds", $email, $amount_saved, $reference);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Payment not verified']);
}
?>
