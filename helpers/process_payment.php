<?php
require_once('../config/config.php');

if (isset($_POST['pay_contribution'])) {
    $member_id = (int) $_POST['member_id'];
    $contribution_id = (int) $_POST['contribution_id'];
    $amount_paid = (float) $_POST['amount_paid'];

    if ($amount_paid <= 0) {
        die("Invalid payment amount.");
    }

     // Get the required contribution amount
     $contribution_query = mysqli_query($mysqli, "SELECT amount FROM contributions WHERE contribution_id = $contribution_id")
     or die("Query Failed: " . mysqli_error($mysqli));
     if (!$contribution_query || mysqli_num_rows($contribution_query) == 0) {
         die("Contribution not found.");
     }
     $contribution = mysqli_fetch_assoc($contribution_query);
     $total_amount_required = $contribution['amount'];
 
     // Get total amount already paid by the member for this contribution
     $paid_query = mysqli_query($mysqli, "SELECT SUM(amount_paid) AS total_paid FROM member_contributions 
     WHERE member_id = $member_id AND contribution_id = $contribution_id")
     or die("Query Failed: " . mysqli_error($mysqli));
     $paid_result = mysqli_fetch_assoc($paid_query);
     $total_paid_so_far = $paid_result['total_paid'] ?? 0;
 
     // Calculate new total after this payment
     $new_total_paid = $total_paid_so_far + $amount_paid;
 
     // Determine the status
     if ($new_total_paid >= $total_amount_required) {
         $status = 'Paid';  // Fully paid
     } else {
         $status = 'Partially Paid';  // Still has balance remaining
     }
 
     // Insert the new payment record
     $query = "INSERT INTO member_contributions (member_id, contribution_id, amount_paid, status, payment_date) 
               VALUES ($member_id, $contribution_id, $amount_paid, '$status', NOW())";
 

    if (mysqli_query($mysqli, $query)) {
        echo "Payment successful! <a href='../views/contributions.php'>Go back to contributions</a>";
        echo "New total paid: $new_total_paid <br>";
        echo "Required amount: $total_amount_required <br>";
        echo "Status: $status <br>";

        // header("Location: ../views/contributions.php");
    } else {
        $_SESSION['error'] = "Something went wrong, please try again!";
    }
} else {
    if (!$contribution_query) {
        die("Error fetching contribution: " . mysqli_error($mysqli));
    }
    if (!$paid_query) {
        die("Error fetching payments: " . mysqli_error($mysqli));
    }
    if (!mysqli_query($mysqli, $query)) {
        die("Error inserting payment: " . mysqli_error($mysqli));
    }
    
}
