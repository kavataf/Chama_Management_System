<?php 
// distribute_dividends.php
require_once("../config/config.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dividend_pool = $_POST['dividend_pool'];  // Total amount of dividends to distribute
    
    // Step 1: Calculate Total Shares in the System
    $total_shares_query = "SELECT SUM(share_amount) AS total_shares FROM shares";
    $result = $mysqli->query($total_shares_query);
    $total_shares = $result->fetch_assoc()['total_shares'];

    if ($total_shares > 0) {
        // Step 2: Calculate Dividend per Share
        $dividend_per_share = $dividend_pool / $total_shares;

        // Step 3: Distribute Dividends to Each Member Based on Their Shares
        $shareholders_query = "SELECT user_id, share_amount FROM shares";
        $shareholders_result = $mysqli->query($shareholders_query);

        while ($shareholder = $shareholders_result->fetch_assoc()) {
            $user_id = $shareholder['user_id'];
            $share_amount = $shareholder['share_amount'];
            $dividend_amount = $share_amount * $dividend_per_share;

            // Insert the dividend distribution for this member
            $stmt = $mysqli->prepare("INSERT INTO dividends (user_id, dividend_amount, distribution_date) 
                                      VALUES (?, ?, NOW())");
            $stmt->bind_param("id", $user_id, $dividend_amount);
            
            if ($stmt->execute()) {
                echo "Dividend of $dividend_amount distributed successfully to user $user_id.<br>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        echo "No shares found in the system.";
    }
}
?>
