<?php
session_start();
require_once('../config/config.php');
require_once('../partials/head.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $stmt = $mysqli->prepare("SELECT user_id, reset_expires FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $reset_expires);
        $stmt->fetch();

        if (strtotime($reset_expires) >= time()) {
            // Token is valid and not expired
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $new_password = $_POST['user_password'];
                $confirm_password = $_POST['confirm_password'];

                if ($new_password !== $confirm_password) {
                    $_SESSION['error'] = "Passwords do not match.";
                } elseif (strlen($new_password) < 6) {
                    $_SESSION['error'] = "Password must be at least 6 characters.";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    $update = $mysqli->prepare("UPDATE users SET user_password = ?, reset_token = NULL, reset_expires = NULL, user_unhashed_password = NULL WHERE user_id = ?");
                    $update->bind_param("si", $hashed_password, $user_id);
                    $update->execute();

                    $_SESSION['success'] = "Password reset successfully. Please log in.";
                    header("Location: login.php");
                    exit;
                }
            }
        } else {
            $_SESSION['error'] = "This reset link has expired.";
        }
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
    }
} else {
    $_SESSION['error'] = "No token provided.";
}

?>
<body class="hold-transition login-page" style="background-image: url('../public/img/bg.jpg'); background-size: cover;">
    <?php
    if (isset($_SESSION['error'])) { echo "<p style='color:red'>{$_SESSION['error']}</p>"; unset($_SESSION['error']); }
    if (isset($_SESSION['success'])) { echo "<p style='color:green'>{$_SESSION['success']}</p>"; unset($_SESSION['success']); }
    ?>
    <?php if (isset($user_id) && strtotime($reset_expires) >= time()) { ?>

        <div class="login-logo">
            <div>
                <img src="../public/img/chama_logo.png" width="100" alt="">
            </div>
            <a href="#"><b>PamojaSave Chama</b></a>
    </div>

    <div class="card" style="width: 450px; height: 220px">
            <div class="card-body login-card-body">
                <p class="login-box-msg text-bold">Reset Password</p>

                <form method="POST">
                    <div class="input-group mb-3">
                        <input type="password" name="user_password" required class="form-control"
                            placeholder="Enter password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock" id="togglePassword"
                                    onclick="togglePasswordVisibility()"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="confirm_password" required class="form-control"
                            placeholder="confirm password">
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    
    <?php } ?>
    <link rel=" stylesheet" href="<?php echo $base_dir; ?>../public/css/adminlte.min.css">

    <?php require_once('../partials/scripts.php'); 
    require_once('../customs/scripts/functions.php');
    ?>

    <style>
    .input-group-text {
        cursor: pointer;
    }
    </style>

    <script>
        function togglePasswordVisibility() {
            const input = document.querySelector('input[name="new_password"]');
            const icon = document.getElementById('togglePassword');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-lock");
                icon.classList.add("fa-lock-open");
            } else {
                input.type = "password";
                icon.classList.remove("fa-lock-open");
                icon.classList.add("fa-lock");
            }
        }
    </script>
</body>
