<!-- add repayment data to database -->
<?php 

if(isset($_POST['loan_details'])){
    $user_id = trim($_POST['user_id']);
    $loan_name = trim($_POST['loan_name']);
    $loan_amount = trim($_POST['loan_amount']);
    $loan_interest = trim($_POST['loan_interest']);
    $processing_fee = trim($_POST['processing_fee']);
    $amount_paid = trim($_POST['amount_paid']);

    // prepare
    $stmt = $mysqli -> prepare("INSERT INTO repayments (user_id, loan_name, 
    loan_amount, loan_interest, processing_fee, amount_paid) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("isiiii", $user_id, $loan_name, $loan_amount, 
    $loan_interest, $processing_fee, $amount_paid);

    if ($stmt->execute()) {
        $_SESSION['success'] = "loan repayment details added successfully";
        // header("location: repayments.php");
        exit;
    } else {
        $err = "Something went wrong, please try again";
    }
}
?>