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
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-12 text-center">
                            <img src="../public/img/merged_logos.png" width="150" alt="">
                            <h2 class="m-0 text-dark">
                                Reports <br>
                            </h2>

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->

            <!-- Main content  executive dashbord-->
            <div class="modal-body">
            <div class="row">
                <?php if ($user_role == 'System Administrator'): ?>
                <div class="col-xl-6 col-md-6">
                    <form method="post">
                        <div class="list-group ">
                            <a href="../reports/pdf/members.php"
                                class="list-group-item list-group-item-action mb-1">
                                <span class="badge badge-primary badge-pill">1</span>
                                All Members
                            </a>
                            <a href="../reports/pdf/expenses.php"
                                class="list-group-item list-group-item-action mb-1">
                                <span class="badge badge-primary badge-pill">2</span>
                                All Expenses

                            </a>
                        </div>
                    </form>
                </div>
                <div class="col-xl-6 col-md-6">
                    <form method="POST">
                        <a href="../reports/pdf/products.php" class="list-group-item list-group-item-action mb-1">
                            <span class="badge badge-primary badge-pill">3</span>
                            All Products

                        </a>
                        <a href="../reports/pdf/contributions.php" class="list-group-item list-group-item-action mb-1">
                            <span class="badge badge-primary badge-pill">4</span>
                            All Contributions
                        </a>


                    </form>
                </div>
            
                <div class="col-xl-6 col-md-6">
                    <form method="post">
                        <div class="list-group">
                            <a href="../reports/pdf/applications.php" class="list-group-item list-group-item-action mb-1">
                                <span class="badge badge-primary badge-pill">5</span>
                                All Applications
                            </a>

                            <a href="../reports/pdf/savings.php" class="list-group-item list-group-item-action">
                                <span class="badge badge-primary badge-pill">6</span>
                                All Savings

                            </a>

                        </div>
                    </form>
                </div>
                <div class="col-xl-6 col-md-6">
                    <form method="POST">
                        <a href="../reports/pdf/" class="list-group-item list-group-item-action">
                            <span class="badge badge-primary badge-pill">7</span>
                            Revenue Collected
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
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