<?php
require_once('../config/config.php');

if (isset($_GET['id'])) {
    $notification_id = intval($_GET['id']);
    $stmt = $mysqli->prepare("UPDATE notifications SET read_status = 'read' WHERE id = ?");
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();
    $stmt->close();
    header("Location: home.php");
    exit();
}
?>
