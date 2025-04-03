<?php 
require_once("../config/config.php");

// Function to Send Contributions Notifications
function sendNotification($user_id, $message) {
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO notifications (member_id, message, status) VALUES (?, ?, 'unread')");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
}

// Notify Members of Pending Contributions
if (isset($_POST['send_notifications'])) {
    $contributions = $mysqli->query("SELECT * FROM contributions");
    while ($contribution = $contributions->fetch_assoc()) {
        $contribution_id = $contribution['contribution_id'];
        $total_amount = $contribution['amount'];
        
        // Get members who have contributed
        $paid_members = [];
        $member_contributions = $mysqli->query("SELECT member_id, SUM(amount_paid) as total_paid 
        FROM member_contributions WHERE contribution_id = $contribution_id GROUP BY member_id");
        while ($member = $member_contributions->fetch_assoc()) {
            $paid_members[$member['member_id']] = $member['total_paid'];
        }
        
        // Get all members
        $all_members = $mysqli->query("SELECT user_id FROM members");
        while ($member = $all_members->fetch_assoc()) {
            $member_id = $member['user_id'];
            if (isset($paid_members[$member_id])) {
                $remaining_amount = $total_amount - $paid_members[$member_id];
                if ($remaining_amount > 0) {
                    sendNotification($member_id, "You have a remaining contribution balance of $$remaining_amount due on " . $contribution['due_date'] . ".");
                }
            } else {
                sendNotification($member_id, "You have a pending contribution of $$total_amount due on " . $contribution['due_date'] . ". Please make your payment.");
            }
        }
    }
echo "Notifications sent successfully.";
}
?>



