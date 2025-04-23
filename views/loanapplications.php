<?php
session_start();
require_once("../config/config.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$sql = "SELECT loan_status, COUNT(*) as total FROM applications GROUP BY loan_status";
$result = $mysqli->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed", "details" => $mysqli->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

if (empty($data)) {
    echo json_encode(["message" => "No loan applications found"]);
} else {
    echo json_encode($data);
}
?>
