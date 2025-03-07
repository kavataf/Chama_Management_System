<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    // Retrieve form data
    $recipientType = $_POST['recipient_type'];
    $message = trim($_POST['message']);

    // Africa's Talking credentials
    $username = "sandbox";  
    $apiKey = "atsk_2c6401ca0a934798f8ef906f6ad278d166a01112fa1d61a4a50e7defccdf058465ac7ed1"; 
    $url = "https://api.sandbox.africastalking.com/version1/messaging";

    // Determine recipient(s)
    if ($recipientType === "single") {
        $phone = trim($_POST['phone']);
        $recipients = $phone;
    } else {
        // Fetch all numbers from the database
        $recipients = "+254711082000,+254722000111"; 
    }

    // Format request data
    $postData = http_build_query([
        'username' => $username,
        'to' => $recipients,
        'message' => $message
    ]);

    $headers = [
        "Accept: application/json",
        "apiKey: $apiKey",
        "Content-Type: application/x-www-form-urlencoded"
    ];

    // cURL request to Africa's Talking API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        echo "Response: " . $response;
    }

    curl_close($ch);
}
?>
