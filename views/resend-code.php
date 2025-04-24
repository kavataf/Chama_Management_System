<?php
session_start();
require_once('../config/config.php');
require 'C:\xampp1\htdocs\COMS\vendor\autoload.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\Exception.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if user session exists
if (!isset($_SESSION['user_email'], $_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'];

// Generate a new code
$auth_code = rand(100000, 999999);
$_SESSION['auth_code'] = $auth_code;
$_SESSION['auth_code_created_at'] = time();


// Send new code
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kavatafaith412@gmail.com'; 
    $mail->Password = 'cfte fgux afpr kvrp'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('kavatafaith412@gmail.com', 'PamojaSave Chama System');
    $mail->addAddress($user_email, $user_name);

    $mail->isHTML(true);
    $mail->Subject = 'Your New Authentication Code';
    $mail->Body    = "
        <p>Hello <strong>$user_name</strong>,</p>
        <p>Your new authentication code is:</p>
        <h2 style='color: #007bff;'>$auth_code</h2>
        <p>Enter this code to continue your login process.</p>
        <br>
        <p>Regards,<br>Your System</p>
    ";

    $mail->send();
    $_SESSION['message'] = "A new authentication code has been sent to your email.";
    header("Location: auth-code.php");
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to resend the code. Please try again.";
    header("Location: auth-code.php");
    exit;
}
