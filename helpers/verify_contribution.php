<?php
require_once('../config/config.php'); 
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$reference = $data['reference'];
$contribution_id = (int) $data['contribution_id'];
$member_id = (int) $data['member_id'];
$amount_paid = (float) $data['amount'];

if (!$reference || !$contribution_id || !$member_id || !$amount_paid) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

// Fetch the required amount from contributions table
$fetch_contribution = $mysqli->query("SELECT amount FROM contributions WHERE contribution_id = $contribution_id");
if (!$fetch_contribution || $fetch_contribution->num_rows == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Contribution not found']);
    exit;
}
$contribution = $fetch_contribution->fetch_assoc();
$amount_required = (float) $contribution['amount'];

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

if ($result && isset($result['data']) && $result['data']['status'] === 'success') {
    // Amount confirmed by Paystack
    $amount_verified = $result['data']['amount'] / 100;

    if ($amount_paid != $amount_verified) {
        echo json_encode(['status' => 'error', 'message' => 'Amount mismatch']);
        exit;
    }

    // Determine status
    if ($amount_paid >= $amount_required) {
        $payment_status = 'Paid';
    } else {
        $payment_status = 'Partially Paid';
    }

    // Insert into member_contributions table
    $stmt = $mysqli->prepare("INSERT INTO member_contributions (contribution_id, member_id, amount_paid, reference, payment_date, status) 
    VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("iisss", $contribution_id, $member_id, $amount_paid, $reference, $payment_status);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed: ' . $stmt->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Payment not verified']);
}
?>
