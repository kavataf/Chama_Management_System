<?php
require_once('../config/config.php');

$data = [];
$labels = [];
$values = [];

// Query to count total members per month
$sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total 
        FROM members 
        GROUP BY month 
        ORDER BY month ASC";

$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month']; // Months (X-axis)
    $values[] = $row['total']; // Total members (Y-axis)
}

// Return JSON response
echo json_encode(["labels" => $labels, "values" => $values]);
?>
