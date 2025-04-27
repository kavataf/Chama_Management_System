<?php 
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\Exception.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\SMTP.php';
require 'C:\xampp1\htdocs\COMS\vendor\autoload.php';
require_once('../config/config.php'); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['member_details'])) {
    $member_name = trim($_POST['member_name']);
    $member_gender = trim($_POST['member_gender']);
    $member_id_no = trim($_POST['member_id_no']);
    $member_email = trim($_POST['member_email']);
    $member_phone = trim($_POST['member_phone']);
    $member_password = bin2hex(random_bytes(8)); // Generate a random password
    $hashed_password = password_hash($member_password, PASSWORD_DEFAULT);
    $access_level = 'Member';
    $status = 'active';

    // Start transaction
    $mysqli->begin_transaction();

    try {
        // Insert into users table
        $stmt = $mysqli->prepare("INSERT INTO users (user_name, user_gender, user_id_no, 
            user_email, user_phone, user_access_level, user_password, user_unhashed_password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $member_name, $member_gender, $member_id_no, $member_email, 
            $member_phone, $access_level, $hashed_password, $member_password);

        if ($stmt->execute()) {
            $user_id = $mysqli->insert_id;
            $_SESSION['user_id'] = $user_id;

            // Insert into members table
            $stmt2 = $mysqli->prepare("INSERT INTO members (user_id, member_name, member_gender, 
                member_email, member_phone, member_id_no, member_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("issssss", $user_id, $member_name, $member_gender, 
                $member_email, $member_phone, $member_id_no, $status);

            if ($stmt2->execute()) {
                $mysqli->commit();

                // Send login credentials via email
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
                    $mail->addAddress($member_email, $member_name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to PamojaSave Chama System';
                    $mail->Body = "
                        <h3>Welcome, $member_name!</h3>
                        <p>Your account has been created successfully. Below are your login credentials:</p>
                        <p><strong>Email:</strong> $member_email<br>
                        <strong>Temporary Password:</strong> $member_password</p>
                        <p>Please log in or set your own password via the link below:</p>
                        <a href='http://localhost/COMS/first-time-login.php'>Set New Password</a>
                        <p>Thank you!</p>
                    ";

                    $mail->send();
                } catch (Exception $e) {
                    $_SESSION['error'] = "Member added but email not sent. Mailer Error: {$mail->ErrorInfo}";
                    header("location: members.php");
                    exit;
                }
                echo "<script>alert('Member added successfully. Password has been sent to the email.');</script>";
                // $_SESSION['success'] = "Member added successfully. Password has been sent to the email.";
                header("location: members.php");
                exit;

            } else {
                throw new Exception("Failed to insert into members table: " . $stmt2->error);
            }
        } else {
            throw new Exception("Failed to insert into users table: " . $stmt->error);
        }
    } catch (Exception $e) {
        $mysqli->rollback();
        $_SESSION['error'] = "Something went wrong, please try again. " . $e->getMessage();
        header("location: members.php");
        exit;
    }
}
?>
