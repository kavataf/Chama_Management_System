<!-- add product data to database -->
<?php 

if(isset($_POST['product_details'])){
    $loan_id = trim($_POST['loan_id']);
    $loan_name = trim($_POST['loan_name']);
    $loan_interest = trim($_POST['loan_interest']);
    $loan_duration = trim($_POST['loan_duration']);
    $processing_fee = trim($_POST['processing_fee']);
    $maximum_limit = trim($_POST['maximum_limit']);
    $loan_guarantors = trim($_POST['loan_guarantors']);
    $member_savings = trim($_POST['member_savings']);
    $loan_penalty = trim($_POST['loan_penalty']);
    $loan_description = trim($_POST['loan_description']);

   // Handle file upload
   $uploadDir = 'uploads/'; // Directory where the file will be saved
   $uploadFile = $uploadDir . basename($_FILES['thumbnail']['name']);

   // Ensure the upload directory exists
   if (!file_exists($uploadDir)) {
       mkdir($uploadDir, 0777, true);
   }

   if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadFile)) {
       // File uploaded successfully, use $uploadFile as the path
       $thumbnail = $uploadFile;
   } else {
       $err = "Failed to upload thumbnail.";
       // Handle the error
   }

    // prepare
    $stmt = $mysqli -> prepare("INSERT INTO products (loan_id, loan_name, loan_interest, loan_duration, processing_fee,
    maximum_limit, loan_guarantors, member_savings, thumbnail, loan_penalty, loan_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("ssisiisisis", $loan_id, $loan_name, $loan_interest, $loan_duration, $processing_fee, $maximum_limit, $loan_guarantors, 
    $member_savings, $thumbnail, $loan_penalty, $loan_description);

    if ($stmt->execute()) {
        $_SESSION['success'] = "product details added successfully";
        // header("location: products.php");
        exit;
    } else {
        $err = "Something went wrong, please try again";
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Directory where the file will be saved
        $uploadFile = $uploadDir . basename($_FILES['thumbnail']['name']);

        // Ensure the upload directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the designated directory
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadFile)) {
            echo "File is valid, and was successfully uploaded.\n";
            // You can now save the file path ($uploadFile) to your database
        } else {
            echo "Possible file upload attack!\n";
        }
    } else {
        echo "File upload error!\n";
    }
}

?>
