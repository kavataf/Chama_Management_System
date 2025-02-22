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
                    <h3>Enter details for loan application</h3>
                    <!-- Loan Application Form -->
                    <form class="needs-validation" method="post" enctype="multipart/form-data">
                    <fieldset class="border p-2 border-success">
                        <legend class="w-auto text-success">Loan / Personal Details</legend>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Loan Amount<span class="text-danger">*</span></label>
                                <input type="number" placeholder="" required name="loan_amount" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Loan Duration<span class="text-danger">*</span></label>
                                <input type="text" placeholder="" required name="loan_duration"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Application Date<span class="text-danger">*</span></label>
                                <input type="date" placeholder="" required name="application_date"
                                    class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Loan Name<span class="text-danger">*</span></label>
                                <input type="text" placeholder="" required name="loan_name"
                                    class="form-control">
                            </div>

                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Loan Purpose<span class="text-danger">*</span></label>
                                <textarea placeholder="" required name="loan_purpose" class="form-control"></textarea>
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="apply_loan" class="btn btn-outline-primary">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </div>


                </form>
                <?php endif; ?>
                <?php require_once('../helpers/applications.php');?>
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