<?php 

if(isset($_POST['product_details'])){
    $loan_id = trim($_POST['loan_id']);
    $loan_name = trim($_POST['loan_name']);
    $loan_duration = trim($_POST['loan_duration']);
    $maximum_limit = trim($_POST['maximum_limit']);
    $loan_guarantors = trim($_POST['loan_guarantors']);
    $member_savings = trim($_POST['member_savings']);
    $loan_description = trim($_POST['loan_description']);


    // prepare
    $stmt = $mysqli -> prepare("INSERT INTO products (loan_id, loan_name, loan_duration,
    maximum_limit, loan_guarantors, member_savings, loan_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("ssisiisisis", $loan_id, $loan_name, $loan_duration, $maximum_limit, $loan_guarantors, 
    $member_savings, $loan_description);

    if ($stmt->execute()) {
        echo "<script>alert('product details added successfully!');</script>";
        // $_SESSION['success'] = "product details added successfully";
        // header("location: products.php");
        exit;
    } else {
        echo "<script>alert('Something went wrong, please try again!');</script>";
        // $err = "Something went wrong, please try again";
    }
}

?>
