<?php
session_start();
require_once("../config/config.php");
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT message FROM notifications WHERE member_id = ? ORDER BY sent_at DESC";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode($notifications);
?>
