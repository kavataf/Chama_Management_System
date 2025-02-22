<!-- add savings data to database -->
<?php 

if(isset($_POST['savings_details'])){
    $user_id = trim($_POST['user_id']);
    $reference_no = trim($_POST['reference_no']);
    $amount = trim($_POST['amount']);
    $date = $_POST['savings_date'];
    $payment_method = trim($_POST['payment_method']);

    // prepare
    $stmt = $mysqli -> prepare("INSERT INTO savings (user_id, reference_no, amount, 
    savings_date, payment_method) VALUES (?, ?, ?, ?, ?)");
    $stmt -> bind_param("isids", $user_id, $reference_no, 
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
