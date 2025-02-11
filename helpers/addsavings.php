<!-- add savings data to database -->
<?php 

if(isset($_POST['savings_details'])){
    $member_name = trim($_POST['member_name']);
    $member_id_no = trim($_POST['member_id_no']);
    $member_phone = trim($_POST['member_phone']);
    $reference_no = trim($_POST['reference_no']);
    $amount = trim($_POST['amount']);
    $date = $_POST['savings_date'];
    $payment_method = trim($_POST['payment_method']);

    // prepare
    $stmt = $mysqli -> prepare("INSERT INTO savings (member_name, member_id_no, member_phone, 
    reference_no, amount, savings_date, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("sssssss", $member_name, $member_id_no, $member_phone, $reference_no, 
    $amount, $date, $payment_method);

    if ($stmt->execute()) {
        $_SESSION['success'] = "savings details added successfully";
        // header("location: savings.php");
        exit;
    } else {
        $err = "Something went wrong, please try again";
    }
}
?>
