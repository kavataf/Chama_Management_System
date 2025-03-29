<?php
session_start();
require_once("../config/config.php");

$user_id = mysqli_real_escape_string($mysqli, $_SESSION['user_id']);

$query = "SELECT mc.contribution_id, c.title, c.due_date, mc.amount_paid, mc.status, mc.payment_date
    FROM member_contributions mc
    JOIN contributions c ON mc.contribution_id = c.contribution_id
    WHERE mc.member_id = $user_id
    ORDER BY mc.payment_date DESC
    LIMIT 5";  

$result = mysqli_query($mysqli, $query);

$transactions = [];

while ($row = mysqli_fetch_assoc($result)) {
    $transactions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($transactions);
?>
