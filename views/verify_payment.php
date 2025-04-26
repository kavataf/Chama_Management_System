<?php
// mysqliect to database
session_start();

require '../config/config.php'; 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}
$user_id = $_SESSION['user_id'];


header('Content-Type: application/json');

// Get JSON POST data
$data = json_decode(file_get_contents('php://input'), true);

$reference = $data['reference'] ?? '';
$email = $data['email'] ?? '';
$amount = $data['amount'] ?? '';

if (empty($reference) || empty($email) || empty($amount)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

// Verify payment with Paystack
$paystack_secret_key = 'sk_test_8f8413288a0652a3a1c0b023db7955eb1f79c6ca'; 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . urlencode($reference));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $paystack_secret_key",
    "Cache-Control: no-cache"
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo json_encode(['status' => 'error', 'message' => 'Curl error: ' . $err]);
    exit;
}

$result = json_decode($response, true);

// Check if transaction was verified
if (isset($result['status']) && $result['status'] === true && isset($result['data']) && $result['data']['status'] === 'success') {
    $amount_saved = $result['data']['amount'] / 100;

    // Insert into savings table
    $stmt = $mysqli->prepare("INSERT INTO savings (user_id, email, amount_saved, reference, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isds", $user_id, $email, $amount_saved, $reference);

    if ($stmt->execute()) {
        // 2. Update member's total savings
        $updateStmt = $mysqli->prepare("UPDATE members SET total_savings = total_savings + ? WHERE user_id = ?");
        $updateStmt->bind_param("di", $amount_saved, $user_id);
        
        if ($updateStmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Saving recorded but failed to update total savings: ' . $updateStmt->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed: ' . $stmt->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Payment not verified']);
}
?>
