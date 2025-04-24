<?php
session_start();
require_once('config/config.php');
require_once('partials/head.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("UPDATE users SET user_password = ?, user_unhashed_password = NULL WHERE user_id = ?");
        $stmt->bind_param("si", $hashed, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Password updated successfully. You can now log in.";
            session_destroy(); // force re-login
            header("location: views/login.php");
            exit;
        } else {
            $_SESSION['error'] = "Error updating password. Please try again.";
        }
    }
}
?>

<body class="hold-transition login-page" style="background-image: url('public/img/bg.jpg'); background-size: cover;">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Pamoja<span>Save</span></b></a>
        </div>

        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header bg-primary text-white text-center">
                <h5>Set Your New Password</h5>
            </div>
            <div class="card-body">

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="input-group mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock" id="togglePassword" onclick="togglePasswordVisibility()"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Set Password</button>
                </form>

            </div>
        </div>

        <link rel="stylesheet" href="<?php echo $base_dir; ?>../public/css/adminlte.min.css">
    </div>

    <?php require_once('partials/scripts.php'); ?>
    <?php require_once('customs/scripts/functions.php'); ?>

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
