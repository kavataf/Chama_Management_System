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

// fetch
$sql = "SELECT r.repayment_id, u.user_name, r.loan_name, r.loan_amount, r.loan_interest, 
r.processing_fee, r.amount_paid, r.repayment_date
          FROM repayments r
          JOIN users u ON r.user_id = u.user_id
          ORDER BY r.repayment_date DESC";
$result = $mysqli -> query($sql);
$repayments = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $repayments[] = $row;
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

                <?php if ($user_role == 'System Administrator') : ?>
                
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Loan Repayments</h5>
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
                                                        <th style="width: 10px">No</th>
                                                        <th>Member Name</th>
                                                        <th>Loan Name</th>
                                                        <th>Loan Amount</th>
                                                        <th style="width: 30px">Loan Interest</th>
                                                        <th style="width: 30px">Processing Fee</th>
                                                        <th>Amount Paid</th>
                                                        <th>Date</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($repayments)) : ?>
                                                    <?php foreach ($repayments as $key => $repayment) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($repayment['user_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($repayment['loan_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($repayment['loan_amount']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($repayment['loan_interest']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($repayment['processing_fee']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($repayment['amount_paid']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($repayment['repayment_date']); ?>
                                                        </td>

                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <tr>
                                                        <td colspan="8">No records found</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php require_once('../modals/add_repayment.php'); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                      <!-- loan repayments chart -->
                <!-- <h3 style="color: #333; font-weight: bold;">🏦 Monthly Loan Repayments</h3>
                <div style="width: 80%; margin: auto;">
                    <canvas id="loanRepaymentsChart"></canvas>
                </div>

                <script>
                    // Data for Monthly Loan Repayments (replace with dynamic PHP data)
                    var months = ['January', 'February', 'March', 'April', 'May', 'June']; // X-axis labels (Months)
                    var repayments = [500, 700, 400, 900, 650, 800]; // Y-axis values (Repayment Amount)

                    var ctx2 = document.getElementById('loanRepaymentsChart').getContext('2d');

                    // Create the bar chart
                    var loanRepaymentsChart = new Chart(ctx2, {
                        type: 'bar', // Bar chart
                        data: {
                            labels: months, // X-axis labels
                            datasets: [{
                                label: 'Monthly Loan Repayments',
                                data: repayments, // Repayment amounts
                                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Bar color
                                borderColor: 'rgba(54, 162, 235, 1)', // Border color
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true // Start Y-axis from 0
                                }
                            }
                        }
                    });
                </script> -->


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