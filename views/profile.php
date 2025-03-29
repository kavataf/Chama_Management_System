<?php
session_start();
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../helpers/users.php');
$user_id = $_SESSION['user_id'];
$user_role= $_SESSION['user_access_level'];

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once('../partials/navbar.php'); ?>
        <?php require_once('../partials/top_navbar.php'); ?>
        <!-- End of Topbar -->
        <div id="content-wrapper" class="d-flex flex-column">


            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="main-content position-relative">
                            <!-- Main nav -->
                            <div class="page-content">
                                <!-- Page title -->
                                <?php
                                    $user_sql = mysqli_query(
                                        $mysqli,
                                        "SELECT * FROM users WHERE user_id = '{$user_id}'"
                                    );
                                    if (mysqli_num_rows($user_sql) > 0) {
                                        while ($user = mysqli_fetch_array($user_sql)) {
                                    ?>
                                <div class="row">
                                    <div class="col-lg-12 order-lg-1">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row align-items-center">
                                                    <div class="col-8">
                                                        <h6 class="h6 mb-0">Change personal details</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <!-- Update User Form -->
                                                <form method="post">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-control-label">Full names</label>
                                                                <input class="form-control" type="text" required
                                                                    name="user_name"
                                                                    value="<?php echo $user['user_name']; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-control-label">Email</label>
                                                                <input class="form-control" type="email"
                                                                    name="user_email"
                                                                    value="<?php echo $user['user_email']; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-control-label">Phone number</label>
                                                                <input class="form-control" type="text"
                                                                    name="user_phone"
                                                                    value="<?php echo $user['user_phone']; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-control-label">ID No</label>
                                                                <input class="form-control" type="text"
                                                                    name="user_id_no"
                                                                    value="<?php echo $user['user_id_no']; ?>">
                                                            </div>
                                                        </div>
                                                        <!-- Hidden field for user ID -->
                                                        <input type="hidden" name="user_id"
                                                            value="<?php echo $user['user_id']; ?>">
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="Update_User"
                                                            class="btn btn-sm btn-primary rounded-pill">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 order-lg-1">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row align-items-center">
                                                    <div class="col-8">
                                                        <h6 class="h6 mb-0">Change password</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <!-- Update Password Form -->
                                                <form method="post">
                                                    <!-- General information -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-control-label">Old password</label>
                                                                <input
                                                                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                                                    class="form-control" type="password" required
                                                                    name="old_password">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-control-label">New password</label>
                                                                <input pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                                                    class="form-control" type="password"
                                                                    name="new_password">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-control-label">Confirm
                                                                    password</label>
                                                                <input pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                                                    class="form-control" type="password"
                                                                    name="confirm_password">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="UpdatePasswords"
                                                            class="btn btn-sm btn-primary rounded-pill">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }
                } ?>
                            </div>
                            <!-- Footer -->
                            <?php require_once('../partials/footer.php'); ?>
                            <!-- End Footer -->
                        </div>

                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->

            <?php require_once('../partials/footer.php');?>
            <!-- End of Footer -->
        </div>
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <?php require_once('../partials/scripts.php'); ?>

    </div>
    <!-- End of Content Wrapper -->



</body>


</html>