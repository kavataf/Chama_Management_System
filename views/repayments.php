<?php
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../partials/head.php');
require_once('../views/loan_functions.php');
$user_role = $_SESSION['user_access_level'];

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repayment_id'], $_POST['amount'])) {
    $repayment_id = $_POST['repayment_id'];
    $amount = floatval($_POST['amount']);

    // Step 1: Get current repayment details including due_date and status
    $stmt = $mysqli->prepare("SELECT loan_amount, amount_paid, due_date, status FROM repayments WHERE repayment_id = ?");
    $stmt->bind_param("i", $repayment_id);
    $stmt->execute();
    $result_check = $stmt->get_result();
    $repayment = $result_check->fetch_assoc();

    if ($repayment) {
        $loan_amount = $repayment['loan_amount'];
        $amount_paid = $repayment['amount_paid'];
        $due_date = new DateTime($repayment['due_date']);
        $today = new DateTime();
        $status = strtolower(trim($repayment['status']));

        // Step 2: Calculate late fee (KES 5 per day after due date)
        $late_fee = 0;
        if ($status === 'overdue') {
            $days_late = $due_date < $today ? $due_date->diff($today)->days : 0;
            $late_fee = $days_late * 50;
        }

        $total_required = $loan_amount + $late_fee;
        $new_paid = $amount_paid + $amount;

        // Step 3: Determine new status
        $new_status = ($new_paid >= $total_required) ? 'paid' : 'pending';

        // Step 4: Update repayment record
        $stmt = $mysqli->prepare("UPDATE repayments SET amount_paid = ?, repayment_date = CURDATE(), status = ? WHERE repayment_id = ?");
        $stmt->bind_param("dsi", $new_paid, $new_status, $repayment_id);
        $stmt->execute();
    }
}

updateOverdue($mysqli);

if ($user_role == 'System Administrator') {
    $result = getAllRepayments($mysqli);
} elseif ($user_role == 'Member') {
    $user_id = $_SESSION['user_id'];
    $result = getRepayments($mysqli, $user_id);
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

                <?php if ($user_role == 'System Administrator') : ?>
                
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">All Loan Repayments</h5>
                                        <a href="#addrepayment" data-toggle="modal" style="text-decoration:none;" class="d-none d-sm-inline-block shadow-sm"><button type="button" class="btn btn-block btn-primary">Add loan repayment</button></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <!-- breakdown table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                    <th>Member ID</th><th>Loan ID</th><th>Due Date</th><th>Amount Due</th>
                                                    <th>Paid</th><th>Status</th><th>Payment Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php while ($row = $result->fetch_assoc()) { ?>
                                                    <tr style="background: <?= $row['status'] == 'overdue' ? '#fdd' : '#dfd' ?>;">
                                                        <td><?= $row['user_id'] ?></td>
                                                        <td><?= $row['loan_id'] ?></td>
                                                        <td><?= $row['due_date'] ?></td>
                                                        <td><?= $row['loan_amount'] ?></td>
                                                        <td><?= $row['amount_paid'] ?></td>
                                                        <td><?= ucfirst($row['status']) ?></td>
                                                        <td><?= $row['repayment_date'] ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                    <?php require_once('../modals/add_repayment.php'); ?>
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
                        <div class="row">
                            <div class="col-lg-12">
                            <h2>My Loan Installments</h2>
                            <table class="table table-bordered" id="datatable">
                                <tr>
                                    <th>Due Date</th><th>Amount Due</th><th>Paid</th><th>Status</th><th>Action</th>
                                </tr>
                                <?php
                                if ($result->num_rows === 0) {
                                    echo "<tr><td colspan='5'>⚠️ No repayments found</td></tr>";
                                }
                                while ($row = $result->fetch_assoc()) {
                                    $loan_amount = $row['loan_amount'];
                                    $due_date = new DateTime($row['due_date']);
                                    $today = new DateTime();
                                    $late_fee = 0;
                                
                                    if (strtolower(trim($row['status'])) === 'overdue') {
                                        $days_late = $due_date->diff($today)->days;
                                        $late_fee = $days_late * 50; // KES 50 per day late fee
                                    }
                                
                                    $total_due = $loan_amount + $late_fee;
                                ?>
                                <tr style="background: <?= $row['status'] == 'overdue' ? '#fdd' : '#dfd' ?>;">
                                    <td><?= $row['due_date'] ?></td>
                                    <td>
                                        <?= $loan_amount ?>
                                        <?php if ($late_fee > 0): ?>
                                            <br><small class="text-danger">Late Fee: <?= $late_fee ?> <br>Total: <?= $total_due ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['amount_paid'] ?></td>
                                    <td><?= ucfirst($row['status']) ?></td>
                                    <td>
                                        <?php
                                        $status = strtolower(trim($row['status']));
                                        if ($status === 'pending' || $status === 'overdue') {
                                        ?>
                                            <form method="POST">
                                                <input type="hidden" name="repayment_id" value="<?= $row['repayment_id'] ?>">
                                                <input type="number" name="amount" step="0.01" 
                                                    max="<?= $total_due ?>" required 
                                                    class="form-control" 
                                                    placeholder="Max: <?= $total_due ?>">
                                                <button type="submit" class="btn btn-outline-success mt-1">Pay</button>
                                            </form>
                                        <?php } else {
                                            echo "✔️ <span class='badge bg-success'>Paid</span>";
                                        } ?>

                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                            </div>
                        </div>

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