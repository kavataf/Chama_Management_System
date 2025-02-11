<?php
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../partials/head.php');
$user_role = $_SESSION['user_access_level'];

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

        <!-- Begin Page Content -->
        <div class="container-fluid">

         
            <div></div>
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->

            <!-- Main content  executive dashbord-->
            <div class="level">

                <?php if ($user_role == 'System Administrator') : ?>
                

                <!-- Members Dashboard -->
                <?php elseif ($user_role == 'Member') : ?>

                <?php endif; ?>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->


        <!-- End of Main Content -->

        <!-- Footer -->
        <?php require_once('../partials/footer.php');?>

        <!-- End of Footer -->


        <!-- End of Page Wrapper -->
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php require_once('../partials/scripts.php'); ?>
</body>


</html>