<?php
// Include PHPMailer classes
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\Exception.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\SMTP.php';
require 'C:\xampp1\htdocs\COMS\config\config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp1\htdocs\COMS\vendor\autoload.php'; 
require_once('../config/config.php');

function sendLoanNotification($mysqli, $memberId, $status, $message, $email) {
    // Save to DB
    $stmt = $mysqli->prepare("INSERT INTO notifications (member_id, message, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $memberId, $message, $status);
    $stmt->execute();
    $stmt->close();

    // Send with PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kavatafaith412@gmail.com';
        $mail->Password   = 'cfte fgux afpr kvrp'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('kavatafaith412@gmail.com', 'Chama System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Loan Notification - Chama System";
        $mail->Body    = nl2br($message); 

        $mail->send();
    } catch (Exception $e) {
        error_log("PHPMailer Error: {$mail->ErrorInfo}");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['application_id']) || empty($_POST['application_id'])) {
        $_SESSION['error'] = "Application ID is missing!";
        header("Location: applications.php");
        exit();
    }

    $application_id = intval($_POST['application_id']);
    $new_status = ($_POST['action'] === 'approve') ? 'Approved' : 'Rejected';

    // Fetch loan + user details
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

    // Get member email
    $stmt_email = $mysqli->prepare("SELECT user_email FROM users WHERE user_id = ?");
    $stmt_email->bind_param("i", $user_id);
    $stmt_email->execute();
    $stmt_email->bind_result($email);
    $stmt_email->fetch();
    $stmt_email->close();

    // Fetch total savings
    $stmt_savings = $mysqli->prepare("SELECT COALESCE(SUM(amount), 0) FROM savings WHERE user_id = ?");
    $stmt_savings->bind_param("i", $user_id);
    $stmt_savings->execute();
    $stmt_savings->bind_result($member_savings);
    $stmt_savings->fetch();
    $stmt_savings->close();

    // Check 30% rule
    if ($new_status == 'Approved' && $member_savings < ($loan_amount * 0.3)) {
        $_SESSION['error'] = "Loan cannot be approved. Member savings are insufficient.";
        header("Location: applications.php");
        exit();
    }

    // Update status
    $stmt_update = $mysqli->prepare("UPDATE applications SET loan_status = ? WHERE application_id = ?");
    $stmt_update->bind_param("si", $new_status, $application_id);

    if (!$stmt_update->execute()) {
        $_SESSION['error'] = "Failed to update status: " . $stmt_update->error;
    } else {
        // === Notify Member ===
        $notifMessage = "Your loan application (ID: $application_id) has been $new_status.";
        $notifStatus = ($new_status == 'Approved') ? 'loan_approved' : 'loan_rejected';
        sendLoanNotification($mysqli, $user_id, $notifStatus, $notifMessage, $email);

        // === If approved, insert repayment schedule ===
        if ($new_status == 'Approved') {
            $interest_rate = 5;
            $interest_amount = $loan_amount * ($interest_rate / 100);
            $total_payable = $loan_amount + $interest_amount;

            $stmt_total = $mysqli->prepare("UPDATE applications SET total_payable = ?, interest_rate = ? 
                WHERE application_id = ?");
            $stmt_total->bind_param("ddi", $total_payable, $interest_rate, $application_id);
            $stmt_total->execute();
            $stmt_total->close();

            $installment = $total_payable / $loan_duration;
            $start_date = date('Y-m-d');

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

        $_SESSION['success'] = "Loan application $new_status successfully!";
    }

    $stmt_update->close();
    header("Location: applications.php");
    exit();

}
?>
