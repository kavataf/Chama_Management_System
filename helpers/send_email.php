<?php
// Include PHPMailer classes
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\Exception.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\SMTP.php';
require 'C:\xampp1\htdocs\COMS\config\config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp1\htdocs\COMS\vendor\autoload.php'; 
require 'C:\xampp1\htdocs\COMS\partials\alert.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    $recipientType = $_POST['recipient_type'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration 
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kavatafaith412@gmail.com'; 
        $mail->Password = 'cfte fgux afpr kvrp'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

         // Send to a single recipient
         if ($recipientType === "single") {
            $recipientEmail = $_POST['recipient'];
            $mail->addAddress($recipientEmail);
        } 
        // Send to all members
        else {
            $query = "SELECT member_email FROM members"; 
            $result = $mysqli->query($query);
            while ($row = $result->fetch_assoc()) {
                $mail->addAddress($row['member_email']);
            }
        }

        // Email content
        $mail->setFrom('kavatafaith412@gmail.com', 'Admin');
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send email
        if ($mail->send()) {
            echo "<script>alert('Email sent successfully!');</script>";
            // $success = "Email sent successfully!";
            // header('location: emails');
        } else {
            echo "<script>alert('Failed to send email!');</script>";
            // $err = "Failed to send email.";
        }
    } catch (Exception $e) {
        $err = "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
}

// home page contact form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sentMessage'])) {
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kavatafaith412@gmail.com'; 
        $mail->Password   = 'cfte fgux afpr kvrp';      
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender & recipient
        $mail->setFrom('kavatafaith412@gmail.com', 'Chama Website');
        $mail->addReplyTo($email, $name);
        $mail->addAddress('kavatafaith412@gmail.com', 'Admin'); 

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission: $subject";
        $mail->Body    = "
            <h3>New Contact Message</h3>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong><br>{$message}</p>
        ";

        // Send the email
        if ($mail->send()) {
            echo "<script>alert('Message has been sent successfully!');</script>";
            // echo "Message has been sent successfully!";
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }

    } catch (Exception $e) {
        echo "Mailer Error (Exception): " . $mail->ErrorInfo;
    }
}
?>
