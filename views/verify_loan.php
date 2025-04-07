<?php
require_once('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['application_id']) || empty($_POST['application_id'])) {
        $_SESSION['error'] = "Application ID is missing!";
        header("Location: applications.php");
        exit();
    }

    $application_id = intval($_POST['application_id']);
    $new_status = ($_POST['action'] === 'approve') ? 'Approved' : 'Rejected';

    // Fetch loan details from applications table
    $query = "SELECT loan_amount, user_id, loan_duration, loan_name, loan_id FROM applications WHERE application_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $stmt->bind_result($loan_amount, $user_id, $loan_duration, $loan_name, $loan_id);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id) {
        $_SESSION['error'] = "Member not found.";
        header("Location: applications.php");
        exit();
    }

    // Fetch total savings of the user
    $query_savings = "SELECT COALESCE(SUM(amount), 0) FROM savings WHERE user_id = ?";
    $stmt_savings = $mysqli->prepare($query_savings);
    $stmt_savings->bind_param("i", $user_id);
    $stmt_savings->execute();
    $stmt_savings->bind_result($member_savings);
    $stmt_savings->fetch();
    $stmt_savings->close();

    // Check if savings meet the 30% loan requirement
    if ($new_status == 'Approved' && $member_savings < ($loan_amount * 0.3)) {
        $_SESSION['error'] = "Loan cannot be approved. Member savings are insufficient.";
        header("Location: applications.php");
        exit();
    }

    // Update loan status in the applications table
    $stmt_update = $mysqli->prepare("UPDATE applications SET loan_status = ? WHERE application_id = ?");
    $stmt_update->bind_param("si", $new_status, $application_id);

    if (!$stmt_update->execute()) {
        $_SESSION['error'] = "Failed to update status: " . $stmt_update->error;
    } else {
        $_SESSION['success'] = "Loan application $new_status successfully!";

        // If loan is approved, insert repayment schedule
        if ($new_status == 'Approved') {
            // Calculate total interest
            $interest_amount = $loan_amount * ($interest_rate / 100);
            $total_payable = $loan_amount + $interest_amount;
        
            // Store total_payable in the applications table
            $stmt_total = $mysqli->prepare("UPDATE applications SET total_payable = ? WHERE application_id = ?");
            $stmt_total->bind_param("di", $total_payable, $application_id);
            $stmt_total->execute();
            $stmt_total->close();
        
            // Calculate installment amount (principal + interest evenly spread)
            $installment = $total_payable / $loan_duration;
            $start_date = date('Y-m-d');
        
            // Insert repayment schedule
            $stmt_insert = $mysqli->prepare("INSERT INTO repayments 
                (user_id, loan_id, loan_name, loan_amount, amount_paid, due_date, status) 
                VALUES (?, ?, ?, ?, 0, ?, 'pending')");
        
            for ($i = 1; $i <= $loan_duration; $i++) {
                $due_date = date('Y-m-d', strtotime("+$i month", strtotime($start_date)));
                $stmt_insert->bind_param("iisds", $user_id, $application_id, $loan_name, $installment, $due_date);
                $stmt_insert->execute();
            }
        
            $stmt_insert->close();
        }
        
        
    }

    $stmt_update->close();
    header("Location: applications.php");
    exit();
}
?>
