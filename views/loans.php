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

$user_id = $_SESSION['user_id'];

// Fetch loan details
$sql = "SELECT a.user_id, a.loan_name, a.loan_status, a.loan_purpose, a.loan_amount, a.loan_duration,
       IFNULL(p.loan_interest, 0) AS interest,
       IFNULL(p.processing_fee, 0) AS processing_fee,
       IFNULL(p.loan_penalty, 0) AS penalty,
       (a.loan_amount + (a.loan_amount * p.loan_interest / 100) + p.processing_fee + IFNULL(p.loan_penalty, 0)) AS total_to_repay,
       a.application_date
FROM applications a
LEFT JOIN products p ON a.loan_id = p.loan_id
WHERE a.user_id = ? 
ORDER BY application_date DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$loans = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $loans[] = $row;
    }
}

// fetch repayments
$sql = "SELECT loan_name, amount_paid, repayment_date FROM repayments WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$repayments = array();
while ($row = $result->fetch_assoc()) {
    $repayments[] = $row;
}

// calculate total repayment amount
$totals = array();
$sql = "SELECT a.loan_name, a.loan_status, a.loan_purpose, a.loan_amount, a.loan_duration,
       IFNULL(p.loan_interest, 0) AS interest,
       IFNULL(p.processing_fee, 0) AS processing_fee,
       IFNULL(p.loan_penalty, 0) AS penalty,
       (a.loan_amount + (a.loan_amount * p.loan_interest / 100) + p.processing_fee + IFNULL(p.loan_penalty, 0)) AS total_to_repay
FROM applications a
LEFT JOIN products p ON a.loan_id = p.loan_id
WHERE a.user_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $totals[] = $row;
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
                

                <!-- Members Dashboard -->
                <?php elseif ($user_role == 'Member') : ?>
                    <!-- Main content -->
                    <div class="content">
                            <div class="row mb-4">
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanapplication')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">Loan Application</span>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanstatus')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">Loan Status</span>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanrepayment')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">Loan Repayment</span>
                                    </a>
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-xl-12">
                            <div id="loanapplication" class="content-section">
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
                            </div>
                        </div>

                            <!-- loan status -->
                            <div class="row">
                            <div class="col-xl-12">
                            <div id="loanstatus" class="content-section">
                            <h3>My loan status.</h3>
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Loan ID</th>
                                        <th>Loan Name</th>
                                        <th>Amount</th>
                                        <th>Amount to repay</th>
                                        <th>Status</th>
                                        <th>Application Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($loans)) : ?>
                                <?php foreach ($loans as $loan) : ?>
                                    <tr>
                                        <td><?php echo $loan['user_id']; ?></td>
                                        <td><?php echo $loan['loan_name']; ?></td>
                                        <td><?php echo number_format($loan['loan_amount'], 2); ?></td>
                                        <td><?php echo number_format($loan['total_to_repay'], 2); ?></td>
                                        <td><?php echo $loan['loan_status']; ?></td>
                                        <td><?php echo date("d M Y", strtotime($loan['application_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan='5'>No loan applications found</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            </div>
                            </div>
                            </div>
                            <!-- loan repayment -->
                            <div class="row">
                            <div class="col-xl-12">
                            <div id="loanrepayment" class="content-section">
                            <h3>Repayment History.</h3>
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Loan Name</th>
                                        <th>Amount Paid</th>
                                        <th>Payment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($repayments)) : ?>
                                <?php foreach ($repayments as $repayment) : ?>
                                <tr>
                                    <td><?php echo $repayment['loan_name']; ?></td>
                                    <td><?php echo number_format($repayment['amount_paid'], 2); ?></td>
                                    <td><?php echo date("d M Y", strtotime($repayment['repayment_date'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan='5'>No loan repayments found</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            </div>
                            </div>
                            </div>
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