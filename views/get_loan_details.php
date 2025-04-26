<?php
require '../config/config.php'; 

if (isset($_GET['loan_name'])) {
    $loan_name = $mysqli->real_escape_string($_GET['loan_name']);

    $sql = "SELECT loan_duration, loan_interest, maximum_limit, loan_description 
            FROM products 
            WHERE loan_name = '$loan_name' 
            LIMIT 1";
    $result = $mysqli->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode([
            'status' => 'success',
            'data' => $row
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Loan not found'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No loan name provided'
    ]);
}
?>
