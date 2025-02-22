<!-- add expense data to database -->
<?php 

if(isset($_POST['expense_details'])){
    $vendor_name = trim($_POST['vendor_name']);
    $expense_type = trim($_POST['expense_type']);
    $reference_no = trim($_POST['reference_no']);
    $expense_amount = trim($_POST['expense_amount']);

    // prepare
    $stmt = $mysqli -> prepare("INSERT INTO expenses (vendor_name, expense_type, reference_no, 
    expense_amount) VALUES (?, ?, ?, ?)");
    $stmt -> bind_param("sssi", $vendor_name, $expense_type, $reference_no, $expense_amount);

    if ($stmt->execute()) {
        $_SESSION['success'] = "expense details added successfully";
        // header("location: expenses.php");
        exit;
    } else {
        $err = "Something went wrong, please try again";
    }
}
?>
