<?php
session_start();
require_once('../config/config.php');
require_once('../partials/head.php');
require 'C:\xampp1\htdocs\COMS\vendor\autoload.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\Exception.php';
require 'C:\xampp1\htdocs\COMS\vendor\PHPMailer-master\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['user_email']);

    $stmt = $mysqli->prepare("SELECT user_id, user_name FROM users WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $user_name);
        $stmt->fetch();

        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $update = $mysqli->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE user_id = ?");
        $update->bind_param("ssi", $token, $expires, $user_id);
        $update->execute();

        // Send Email
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
            $mail->addAddress($email, $user_name);

            // Content
            $mail->isHTML(false);
            $mail->Subject = 'Password Reset Request';
            $reset_link = "http://localhost/COMS/views/reset-password.php?token=$token";
            $mail->Body = "Hi $user_name,\n\nClick the link below to reset your password:\n$reset_link\n\nThis link expires in 1 hour.";
            $mail->send();

            $_SESSION['success'] = "Reset instructions sent to your email.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Email not sent: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = "Email not found.";
    }

    header("Location: forgot-password.php");
    exit;
}
?>
<body class="hold-transition login-page" style="background-image: url('../public/img/bg.jpg'); background-size: cover;">
    <?php if (isset($_SESSION['error'])) { echo "<p style='color:red'>{$_SESSION['error']}</p>"; unset($_SESSION['error']); } ?>
    <?php if (isset($_SESSION['success'])) { echo "<p style='color:green'>{$_SESSION['success']}</p>"; unset($_SESSION['success']); } ?>

    <div class="login-logo">
            <div>
                <img src="../public/img/chama_logo.png" width="100" alt="">
            </div>
            <a href="#"><b>PamojaSave Chama</b></a>
    </div>

    <div class="card" style="width: 450px; height: 220px">
            <div class="card-body login-card-body">
                <p class="login-box-msg text-bold">Forgot Password</p>

                <form method="post">
                    <div class="input-group mb-3">
                        <input type="email" name="user_email" required class="form-control" placeholder="Enter your email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button type="submit" name="Login" class="btn btn-success btn-block">Send Reset Link</button>
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    <link rel=" stylesheet" href="<?php echo $base_dir; ?>../public/css/adminlte.min.css">
</body>
