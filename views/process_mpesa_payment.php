<?php
require_once '../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);

// Get the payment response details
$reference = $data['reference']; 
$repayment_id = $data['repayment_id'];  
$amount = $data['amount'];  

// Verify the payment with Paystack API
$paystack_url = 'https://api.paystack.co/transaction/verify/' . $reference;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $paystack_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer sk_test_8f8413288a0652a3a1c0b023db7955eb1f79c6ca', 
]);

$response = curl_exec($ch);
curl_close($ch);

// Log the raw response to check its structure
file_put_contents('paystack_response.log', $response);

$transaction = json_decode($response, true);

// Check if the response is valid JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON response from Paystack']);
    exit;
}

// Check the payment status
if ($transaction && $transaction['data']['status'] === 'success') {
    // Payment was successful, update repayment details

    // Get the current repayment details
    $stmt = $mysqli->prepare("SELECT loan_amount, amount_paid, due_date, status FROM repayments WHERE repayment_id = ?");
    $stmt->bind_param("i", $repayment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $repayment = $result->fetch_assoc();

    if ($repayment) {
        $loan_amount = $repayment['loan_amount'];
        $amount_paid = $repayment['amount_paid'];
        $total_due = $loan_amount; 
        $new_amount_paid = $amount_paid + $amount;

        // Calculate late fee if the payment is overdue
        $due_date = new DateTime($repayment['due_date']);
        $today = new DateTime();
        $late_fee = 0;

        if ($repayment['status'] == 'overdue' && $due_date < $today) {
            $days_late = $due_date->diff($today)->days;
            $late_fee = $days_late * 50; 
        }

        // Calculate total required amount (loan amount + late fees)
        $total_required = $total_due + $late_fee;

        // Update the repayment status based on the payment
        $new_status = ($new_amount_paid >= $total_required) ? 'paid' : 'pending';

        // Update the repayment record
        $stmt = $mysqli->prepare("UPDATE repayments SET amount_paid = ?, repayment_date = CURDATE(), status = ? WHERE repayment_id = ?");
        $stmt->bind_param("dsi", $new_amount_paid, $new_status, $repayment_id);
        $stmt->execute();

        // Send success response
        echo json_encode(['status' => 'success', 'message' => 'Payment verified and recorded']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Repayment not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Payment verification failed']);
}
?>
