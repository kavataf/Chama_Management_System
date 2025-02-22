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

$applications = array();
$sql = "SELECT a.*, u.user_name
FROM applications a
JOIN users u
WHERE u.user_id = a.user_id";
$result = $mysqli -> query($sql);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $applications[] = $row;
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

         
            <div></div>
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->

            <!-- Main content  executive dashbord-->
            <div class="level">

                <?php if ($user_role == 'System Administrator') : ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Loan Application Status</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <!-- breakdown table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">No</th>
                                                        <th>Member Name</th>
                                                        <th>Loan Name</th>
                                                        <th>Loan Amount</th>
                                                        <th>Loan Duration</th>
                                                        <th>Application Date</th>
                                                        <th>Purpose</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($applications)) : ?>
                                                    <?php foreach ($applications as $key => $application) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($application['user_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($application['loan_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($application['loan_amount']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($application['loan_duration']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($application['application_date']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($application['loan_purpose']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($application['loan_status']); ?>
                                                        </td>
                                                        <td>
                                                            <form method="POST" action="verify_loan.php">
                                                                <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
                                                                <button class="badge badge-success mb-2" type="submit" name="action" value="approve">Approve</button>
                                                                <button class="badge badge-danger" type="submit" name="action" value="reject">Reject</button>
                                                            </form>
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
                            </div>
                        </div>
                    </div>

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