<?php
session_start();
require_once('../config/config.php');
require_once('../partials/head.php');

// Redirect if auth code is not set
if (!isset($_SESSION['auth_code']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = trim($_POST['auth_code']);

    $auth_code_lifetime = 5 * 60; 

    if (!isset($_SESSION['auth_code'], $_SESSION['auth_code_created_at'])) {
        $error = "Authentication code expired or missing. Please request a new one.";
    } elseif (time() - $_SESSION['auth_code_created_at'] > $auth_code_lifetime) {
        $error = "Your authentication code has expired. Please request a new one.";
        unset($_SESSION['auth_code'], $_SESSION['auth_code_created_at']);
    } elseif ($entered_code == $_SESSION['auth_code']) {
        // Success
        $_SESSION['logged_in'] = true;
        unset($_SESSION['auth_code'], $_SESSION['auth_code_created_at']);
        header("Location: home.php");
        exit;
    } else {
        $error = "Invalid authentication code.";
    }

}
?>

<body class="hold-transition login-page" style="background-image: url('../public/img/bg.jpg'); background-size: cover;">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box">
            <div class="card">
                <div class="card-body login-card-body">
                    <h3 class="text-dark mt-5">Two-Step Verification</h3>
                    <p>We sent a code to your email. Please enter it below.</p>
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="success"><?= htmlspecialchars($_SESSION['message']) ?></div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="post" style="width: 500px; margin: auto 20px;">
                        <div class="input-group mb-3">
                            <input type="text" name="auth_code" id="auth_code" required maxlength="6" placeholder="Enter code"
                                class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <p class="mb-1">
                                    <p id="countdown" style="font-weight: bold; color: #007bff;"></p>
                                </p>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary mr-4">Verify</button> 
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    <p><a href="resend-code.php">Didn't receive the code?</a></p>
                </div>
            </div>
        </div>
    </div>


    <script>
    const expiryTime = <?= $_SESSION['auth_code_created_at'] + 300 ?> * 1000; 

    function startCountdown() {
        const timerEl = document.getElementById('countdown');
        const interval = setInterval(() => {
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance <= 0) {
                clearInterval(interval);
                timerEl.textContent = "Code expired. Please request a new one.";
                timerEl.style.color = "red";
            } else {
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                timerEl.textContent = `Code expires in ${minutes}m ${seconds}s`;
            }
        }, 1000);
    }

    startCountdown();
</script>

</body>
</html>
