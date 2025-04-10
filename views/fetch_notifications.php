<?php
session_start();
require_once("../config/config.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Session not set"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT id, message, read_status, sent_at 
          FROM notifications 
          WHERE member_id = ? 
          ORDER BY sent_at DESC";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "Prepare failed: " . $mysqli->error]);
    exit;
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    echo json_encode(["error" => "Execute failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        'id' => $row['id'],
        'message' => $row['message'],
        'read_status' => $row['read_status'],
        'sent_at' => $row['sent_at']
    ];
}

echo json_encode($notifications);
?>
