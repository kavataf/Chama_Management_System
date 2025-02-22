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
$sql = "SELECT s.savings_id, u.user_name, s.reference_no, s.amount, s.savings_date, s.payment_method 
          FROM savings s
          JOIN users u ON s.user_id = u.user_id
          ORDER BY s.savings_date DESC";
$result = $mysqli -> query($sql);
$savings = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $savings[] = $row;
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
                                        <h5 class="card-title m-0">Savings</h5>
                                        <a href="#addsavings" data-toggle="modal" style="text-decoration:none;" class="d-none d-sm-inline-block shadow-sm"><button type="button" class="btn btn-block btn-primary">Add new savings</button></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <!-- table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">No</th>
                                                        <th>Member Name</th>
                                                        <th>Ref. No</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th>Payment Method</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($savings)) : ?>
                                                    <?php foreach ($savings as $key => $saving) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($saving['user_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['reference_no']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['amount']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['savings_date']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['payment_method']); ?>
                                                        </td>
                                                        <td class="sorting_1" style="width: 15%;">
                                                            <a data-toggle="modal" href="#updatesaving"
                                                                class="badge badge-primary"
                                                                data-id="<?php echo $saving['savings_id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($saving['user_name']); ?>"
                                                                data-ref="<?php echo htmlspecialchars($saving['reference_no']); ?>"
                                                                data-amount="<?php echo htmlspecialchars($saving['amount']); ?>"
                                                                data-date="<?php echo htmlspecialchars($saving['savings_date']); ?>"
                                                                data-method="<?php echo htmlspecialchars($saving['payment_method']); ?>"
                                                                onclick="setUpdateModalData(this)">
                                                                <i class="fas fa-edit"></i> Update
                                                            </a>
                                                            <a class="badge badge-danger" 
                                                            data-id="<?php echo htmlspecialchars($saving['savings_id']);?>" href="#deleteModal"
                                                            data-toggle="modal">
                                                                <i class="fas fa-trash"></i> Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <tr>
                                                        <td colspan="8">No records found</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php require_once('../modals/add_savings.php'); ?>
                                                    <?php require_once('../modals/manage_savings.php'); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                         
                <!-- total savings vs withdrawals chart -->
                <h3 style="color: #333; font-weight: bold;">🏦 Total Savings vs. Withdrawals</h3>
                <div style="width: 80%; margin: auto;">
                    <canvas id="savingsWithdrawalsChart"></canvas>
                </div>

                <script>
                    // Sample Data: Replace with actual data from your database
                    const data = {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June'], // X-axis (Months)
                        datasets: [{
                                label: 'Total Savings',
                                data: [1000, 2000, 3000, 2500, 2200, 1800], // Y-axis (Savings amounts)
                                backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue color for Savings
                                borderColor: 'rgba(54, 162, 235, 1)', // Border color
                                borderWidth: 1
                            },
                            {
                                label: 'Total Withdrawals',
                                data: [500, 800, 1000, 1200, 900, 1100], // Y-axis (Withdrawals amounts)
                                backgroundColor: 'rgba(255, 99, 132, 0.6)', // Red color for Withdrawals
                                borderColor: 'rgba(255, 99, 132, 1)', // Border color
                                borderWidth: 1
                            }
                        ]
                    };

                    // Chart configuration
                    const config = {
                        type: 'bar', // Bar chart type
                        data: data,
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    stacked: true, // Enable stacking for X-axis
                                },
                                y: {
                                    stacked: true, // Enable stacking for Y-axis
                                    beginAtZero: true // Start Y-axis from 0
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top', // Position of the legend
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            }
                        }
                    };

                    // Render the chart
                    const ctx = document.getElementById('savingsWithdrawalsChart').getContext('2d');
                    const savingsWithdrawalsChart = new Chart(ctx, config);
                </script>


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