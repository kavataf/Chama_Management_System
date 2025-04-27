<?php

use Com\Tecnick\Barcode\Type\Square\Datamatrix\Encode;

require_once('../config/config.php');

$data = [
    "approved_loans" => 0,
    "savings" => 0,
    "repayments" => 0
];

$sql = "SELECT SUM(loan_amount) AS loan_amount 
FROM applications WHERE loan_status = 'Approved'";
$result = $mysqli->query($sql);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $data["approved_loans"] = $row['loan_amount'];
    }
}

$sql = "SELECT SUM(amount_saved) AS savings_amount 
FROM savings";
$result = $mysqli->query($sql);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $data["savings"] = $row['savings_amount'];
    }
}

$sql = "SELECT SUM(amount_paid) AS repaid_amount 
FROM repayments";
$result = $mysqli->query($sql);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $data["repayments"] = $row['repaid_amount'];
    }
}

$total = $data["approved_loans"] + $data["savings"] + $data["repayments"];

if($total > 0){
    $data["approved_loans"] = round(($data["approved_loans"] / $total * 100), 2);
    $data["savings"] = round(($data["savings"] / $total * 100), 2);
    $data["repayments"] = round(($data["repayments"] / $total * 100), 2);
}

header('Content-Type: application/json');
echo json_encode($data);
?>