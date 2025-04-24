<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp1\htdocs\COMS\vendor\autoload.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\Exception.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\SMTP.php';

// Make sure session contains needed values
if (!isset($_SESSION['user_email'], $_SESSION['user_name'], $_SESSION['auth_code'])) {
    die("Missing session data. Cannot send auth code.");
}

$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'];
$auth_code = $_SESSION['auth_code'];

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kavatafaith412@gmail.com'; 
    $mail->Password = 'cfte fgux afpr kvrp'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('kavatafaith412@gmail.com', 'PamojaSave Chama System');
    $mail->addAddress($user_email, $user_name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Authentication Code';
    $mail->Body    = "
        <p>Hello <strong>$user_name</strong>,</p>
        <p>Your authentication code is:</p>
        <h2 style='color: #007bff;'>$auth_code</h2>
        <p>Please enter this code to complete your login.</p>
        <br>
        <p>Regards,<br>Your System</p>
    ";

    $mail->send();
    // Optional: echo "Email sent successfully.";
} catch (Exception $e) {
    // Optional: log or display error
    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
}
