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

$dividends = array();
// Query to fetch all dividend records
$dividends_query = "SELECT d.id, u.member_name, d.dividend_amount, d.distribution_date
                    FROM dividends d
                    JOIN members u ON d.user_id = u.user_id
                    ORDER BY d.distribution_date DESC";

$dividends_result = $mysqli->query($dividends_query);
if($dividends_result -> num_rows > 0){
    while($row = $dividends_result -> fetch_assoc()){
        $dividends[] = $row;
    }
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
            <!-- Main content  executive dashbord-->
            <div class="level">
                    <div class="row mb-4">
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanapplication')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">Distribute Dividends</span>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanstatus')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">View Dividends</span>
                                    </a>
                                </div>
                            </div>

                    </div>

                        <div class="row">
                            <div class="col-lg-12">
                            <div id="loanapplication" class="content-section">
                            <h2>Distribute Dividends</h2>
                                <form class="needs-validation" method="post" enctype="multipart/form-data" action="../helpers/distribute_dividends.php">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="dividend_pool">Total Dividend Pool:<span class="text-danger">*</span></label>
                                                <input type="number" placeholder="" id="dividend_pool" required 
                                                name="dividend_pool" class="form-control" step="0.01">
                                            </div>
                                        </div>
                                        
                                    <div class="modal-footer">
                                        <div class="text-right">
                                            <button type="submit" value="Distribute Dividends" class="btn btn-outline-primary">
                                                <i class="fas fa-save"></i> Distribute
                                            </button>
                                        </div>
                                    </div>


                                </form>
                            </div>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-xl-12">
                                <div id="loanstatus" class="content-section">
                                    <h3>All Members' Dividends</h3>
                                        <table class="table table-bordered" id="datatable">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10px">No</th>
                                                            <th>Member Name</th>
                                                            <th>Dividend Amount</th>
                                                            <th>Distribution Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($dividends)) : ?>
                                                        <?php foreach ($dividends as $key => $dividend) : ?>
                                                        <tr>
                                                            <td><?php echo ($key + 1); ?></td>
                                                            <td><?php echo htmlspecialchars($dividend['member_name']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($dividend['dividend_amount']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($dividend['distribution_date']); ?>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        <?php else : ?>
                                                        <tr>
                                                            <td colspan="8">No records found</td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                </div>
                                </div>
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
    <?php require_once('../customs/scripts/functions.php'); ?>
    <?php require_once('../customs/scripts/ajax.php'); ?>

    <script>
    function showSection(sectionId) {
        // Hide all sections
        var sections = document.getElementsByClassName('content-section');
        for (var i = 0; i < sections.length; i++) {
            sections[i].style.display = 'none';
        }

        // Show the selected section
        document.getElementById(sectionId).style.display = 'block';
    }

    $(document).ready(function() {
        $('#datatable').DataTable();
    });
    </script>

    <?php require_once('../partials/scripts.php'); ?>
</body>


</html>