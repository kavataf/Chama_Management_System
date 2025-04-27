<?php 
require_once("../config/config.php");

if (isset($_POST['apply_loan'])) {
    $user_id = $_SESSION['user_id']; 
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

    // Fetch loan_id and penalty details from products table
    $stmt = $mysqli->prepare("SELECT loan_id, loan_penalty FROM products WHERE loan_name = ?");
    $stmt->bind_param("s", $loan_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $loan_id = $row['loan_id']; 
        $penalty = $row['loan_penalty']; 
    } else {
        $_SESSION['error'] = "Selected loan does not exist!";
        header("Location: loans.php");
        exit();
    }

    // Insert loan application with loan_id
    $stmt = $mysqli->prepare("INSERT INTO applications (user_id, loan_id, loan_amount, loan_duration, loan_purpose, 
        loan_name, loan_status, application_date) VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?)");
    $stmt->bind_param("iiiisss", $user_id, $loan_id, $loan_amount, $loan_duration, $loan_purpose, $loan_name, $application_date);

    if ($stmt->execute()) {
        echo "<script>alert('Loan application submitted successfully!');</script>";
        // $_SESSION['success'] = "Loan application submitted successfully!";
        // header("Location: loans.php");
        exit();
    } else {
        echo "<script>alert('Something went wrong, please try again!');</script>";
        // $_SESSION['error'] = "Something went wrong, please try again!";
    }
}

?>
