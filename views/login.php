<?php 
session_start();
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../helpers/auth.php');
?>
<body class="hold-transition login-page" style="background-image: url('../public/img/bg.jpg'); background-size: cover;">
    <div class="login-box">
        <div class="login-logo">
            <div>
                <!-- <img src="../public/img/small-logo.png" width="100" alt=""> -->
            </div>
            <a href="#"><b>Chama Management System </b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign In</p>

                <form method="post">
                    
                    <div class="input-group mb-3">
                        <input type="email" name="user_email" required class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="user_password" required class="form-control" id="password"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock" id="togglePassword"
                                    onclick="togglePasswordVisibility()"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" name="Login" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="#">I forgot my password</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    <link rel=" stylesheet" href="<?php echo $base_dir; ?>../public/css/adminlte.min.css">

    </div>
    <!-- /.login-box -->

    <?php require_once('../partials/scripts.php'); 
    require_once('../customs/scripts/functions.php');
    ?>

    <style>
    .input-group-text {
        cursor: pointer;
    }
    </style>

</body>