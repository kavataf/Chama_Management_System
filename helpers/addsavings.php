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
// purchase_shares.php

if (isset($_POST['purchase_shares'])) {
    $user_id = $_SESSION['user_id'];
    $share_amount = $_POST['share_amount'];

    // Insert share purchase into shares table
    $stmt = $mysqli->prepare("INSERT INTO shares (user_id, share_amount, purchase_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $share_amount);
    
    if ($stmt->execute()) {
         $_SESSION['success'] = "Shares purchased successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

}

if(isset($_POST['shares_details'])){
    $user_id = trim($_POST['user_id']);
    $reference_no = trim($_POST['share_amount']);
    $amount = trim($_POST['purchase_date']);

    // prepare
    $stmt = $mysqli -> prepare("INSERT INTO shares (user_id, share_amount, purchase_date) VALUES (?, ?, ?)");
    $stmt -> bind_param("iis", $user_id, $reference_no, 
    $amount);

    if ($stmt->execute()) {
        $_SESSION['success'] = "shares details added successfully";
        // header("location: savings.php");
        exit;
    } else {
        $err = "Something went wrong, please try again";
    }
}

?>
