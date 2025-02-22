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

    // Fetch loan amount and user_id from applications table
    $query = "SELECT loan_amount, user_id FROM applications WHERE application_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $stmt->bind_result($loan_amount, $user_id);
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
    }

    $stmt_update->close();
    header("Location: applications.php");
    exit();
}
?>
