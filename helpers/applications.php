<?php 
require_once("../config/config.php");

if (isset($_POST['apply_loan'])) {
    $user_id = $_SESSION['user_id']; // Get user_id from session
    $loan_amount = $_POST['loan_amount'];
    $loan_duration = $_POST['loan_duration'];
    $loan_purpose = $_POST['loan_purpose'];
    $loan_name = $_POST['loan_name'];
    $application_date = $_POST['application_date'];

    if (!$user_id) {
        $_SESSION['error'] = "User not logged in.";
        header("Location: loans.php");
        exit();
    }

    // Insert loan application
    $stmt = $mysqli->prepare("INSERT INTO applications (user_id, loan_amount, loan_duration, loan_purpose, 
        loan_name, loan_status, application_date) VALUES (?, ?, ?, ?, ?, 'Pending', ?)");
    $stmt->bind_param("iissss", $user_id, $loan_amount, $loan_duration, $loan_purpose, $loan_name, $application_date);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Loan application submitted successfully!";
        header("Location: loans.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong, please try again!";
    }
}
?>
