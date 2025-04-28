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

        $stmt = $mysqli->prepare("UPDATE users SET user_password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $hashed, $user_id);

        if ($stmt->execute()) {
            echo "<script>
                alert('Password updated successfully. You can now log in.');
            </script>";

            session_destroy(); 
            header("Location: views/login.php"); 
            exit; 

        } else {
            echo "<script>
                alert('Error updating password. Please try again.');
            </script>";
            exit; 
            // $_SESSION['error'] = "Error updating password. Please try again.";
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

                <!-- <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?> -->

                <form method="post">
                    <div class="input-group mb-3 d-flex flex-column">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="d-flex flex-lg-row">
                            <input type="password" class="form-control" name="new_password" id="new_password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock" id="togglePassword" onclick="togglePasswordVisibility()"></span>
                                </div>
                            </div>
                        </div>
                        <div id="password-strength-message" style="font-size: 12px; color: red;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                        <div id="password-match-message" style="font-size: 12px; color: red;"></div>
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


        // Password strength checker
        document.getElementById("new_password").addEventListener("input", function() {
            const password = this.value;
            const strengthMessage = document.getElementById("password-strength-message");
            
            let strength = "Weak";
            let color = "red";
            let message = "Password must be at least 8 characters long, with uppercase, lowercase, digits, and symbols.";

            // Check password strength
            if (password.length >= 8) {
                if (/[A-Z]/.test(password) && /[a-z]/.test(password) && /\d/.test(password) && /[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    strength = "Strong";
                    color = "green";
                    message = "Password is strong!";
                } else if (/[A-Z]/.test(password) && /[a-z]/.test(password) && /\d/.test(password)) {
                    strength = "Moderate";
                    color = "orange";
                    message = "Password should include symbols.";
                }
            }
            
            strengthMessage.textContent = message;
            strengthMessage.style.color = color;
        });

        // Confirm password match checker
        document.getElementById("confirm_password").addEventListener("input", function() {
            const newPassword = document.getElementById("new_password").value;
            const confirmPassword = this.value;
            const matchMessage = document.getElementById("password-match-message");
            
            if (newPassword !== confirmPassword) {
                matchMessage.textContent = "Passwords do not match!";
                matchMessage.style.color = "red";
            } else {
                matchMessage.textContent = "Passwords match!";
                matchMessage.style.color = "green";
            }
        });

    </script>
</body>
