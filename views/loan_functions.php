<?php
require_once("../config/config.php");

// Generate installments when a loan is approved
function generateInstallments($mysqli, $loan_id) {
    $loan = $mysqli->query("SELECT * FROM applications WHERE application_id = $loan_id")->fetch_assoc();
    $monthly = $loan['total_payable'] / $loan['loan_duration'];
    $start = new DateTime($loan['application_date']);
    $user_id = $loan['user_id']; 
    $loan_name = $loan['loan_name'];

    for ($i = 1; $i <= $loan['loan_duration']; $i++) {
        $due = clone $start;
        $due->modify("+$i month");
        $mysqli->query("INSERT INTO repayments (loan_id, user_id, loan_name, due_date, loan_amount)
                VALUES ($loan_id, $user_id, '$loan_name', '{$due->format('Y-m-d')}', $monthly)");

    }
}


// Mark overdue payments
function updateOverdue($mysqli) {
    $today = date('Y-m-d');

    // Update all repayments where due_date < today AND status is still 'pending'
    $query = "UPDATE repayments 
              SET status = 'overdue' 
              WHERE due_date < ? 
              AND (status = 'pending' OR status = '')";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $stmt->close();
}



// Make a payment
function makeRepayment($mysqli, $repayment_id, $amount) {
    $today = date('Y-m-d');
    $stmt = $mysqli->prepare("UPDATE repayments 
                            SET amount_paid = ?, repayment_date = ?, status = 'paid' 
                            WHERE repayment_id = ?");
    $stmt->bind_param("dsi", $amount, $today, $repayment_id);
    $stmt->execute();
}

// Get member repayments
function getRepayments($mysqli, $user_id) {
    $sql = "SELECT r.*, a.user_id 
            FROM repayments r
            JOIN applications a ON r.loan_id = a.application_id
            WHERE a.user_id = ?
            ORDER BY r.due_date ASC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}



// Get all for admin
function getAllRepayments($mysqli) {
    return $mysqli->query("SELECT r.*, l.user_id 
                         FROM repayments r
                         JOIN applications l ON r.loan_id = l.application_id
                         ORDER BY due_date ASC");
}

function generateRepaymentSchedule($mysqli, $user_id, $loan_id, $loan_amount, $duration) {
    $installment_amount = $loan_amount / $duration;
    $start_date = date('Y-m-d');

    for ($i = 1; $i <= $duration; $i++) {
        $due_date = date('Y-m-d', strtotime("+$i month", strtotime($start_date)));

        $stmt = $mysqli->prepare("INSERT INTO repayments 
            (user_id, loan_id, loan_name, loan_amount, amount_paid, due_date, status) 
            VALUES (?, ?, ?, ?, 0, ?, 'pending')");
        $loan_name = "Loan #$loan_id"; // or fetch actual name
        $stmt->bind_param("iisds", $user_id, $loan_id, $loan_name, $installment_amount, $due_date);
        $stmt->execute();
    }
}

?>
