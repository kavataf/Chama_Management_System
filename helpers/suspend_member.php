<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Check for the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');  // Read the input JSON body
    $data = json_decode($input, true);  // Decode the JSON data into an associative array

    if (isset($data['id']) && isset($data['action']) && $data['action'] === 'suspend') {
        $memberId = intval($data['id']);  // Get the member ID from the data

        // Include your DB connection
        require_once("../config/config.php");

        // Prepare and execute the query to suspend the member
        $stmt = $conn->prepare("UPDATE members SET status = 'suspended' WHERE id = ?");
        $stmt->bind_param("i", $memberId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Member suspended successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to suspend member.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing data or invalid action.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>