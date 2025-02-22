<!-- add repayment data to database -->
<?php 

if(isset($_POST['loan_details'])){
    $member_name = trim($_POST['member_name']);
    $member_id_no = trim($_POST['member_id_no']);
    $loan_name = trim($_POST['loan_name']);
    $loan_amount = trim($_POST['loan_amount']);
    $loan_interest = trim($_POST['loan_interest']);
    $processing_fee = trim($_POST['processing_fee']);
    $amount_paid = trim($_POST['amount_paid']);

    // prepare
    $stmt = $mysqli -> prepare("INSERT INTO repayments (member_name, member_id_no, loan_name, 
    loan_amount, loan_interest, processing_fee, amount_paid) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("sisiiii", $member_name, $member_id_no, $loan_name, $loan_amount, 
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