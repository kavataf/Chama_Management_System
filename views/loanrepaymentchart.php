<?php 
session_start();
require_once("../config/config.php");

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// member loanRepaymentChart
$user_id = $_SESSION['user_id'];
$loanRepaymentChart = "SELECT user_id, repayment_date, SUM(amount_paid) AS total_amount
FROM repayments 
WHERE user_id = ? 
GROUP BY repayment_date 
ORDER BY repayment_date ASC;";

$stmt = $mysqli -> prepare($loanRepaymentChart);
$stmt -> bind_param("i", $user_id);
$stmt -> execute();
$result = $stmt -> get_result();

$labels = [];
$data = [];

while($row = $result -> fetch_assoc()){
    $labels[] = $row['repayment_date'];
    $data[] = $row['total_amount'];
}

$stmt -> close();

if (empty($labels) || empty($data)) {
    echo json_encode(["message" => "No repayments found"]);
} else {
    echo json_encode(['labels' => $labels, 'data' => $data]);
}

?>