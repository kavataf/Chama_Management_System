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

$sql = "SELECT COUNT(*) AS total_members FROM members";

$result = $mysqli -> query($sql);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $total_members = $row['total_members'];
    }
}

$user_id = $_SESSION['user_id'];

$sql2 = "SELECT SUM(amount) AS amount, user_id
FROM savings 
WHERE user_id = ?";

$stmt = $mysqli -> prepare($sql2);
$stmt -> bind_param("i", $user_id);
$stmt -> execute();
$stmt -> bind_result($amount, $fetched_user_id);
$stmt -> fetch();
$stmt -> close();

$sql3 = "SELECT SUM(loan_amount) AS loan, user_id 
FROM applications WHERE user_id = ?";

$stmt = $mysqli -> prepare($sql3);
$stmt -> bind_param("i", $user_id);
$stmt -> execute();
$stmt -> bind_result($loan, $fetched_user_id);
$stmt -> fetch();
$stmt -> close();

$sql4 = "SELECT COUNT(*) AS loan_applications FROM applications";

$result = $mysqli -> query($sql4);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $loans = $row['loan_applications'];
    }
}

$sql5 = "SELECT COUNT(*) AS contributions FROM contributions";

$result = $mysqli -> query($sql5);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $contributions = $row['contributions'];
    }
}

$unpaid_query = mysqli_query($mysqli, 
    "SELECT COUNT(*) AS unpaid_count 
    FROM contributions c 
    LEFT JOIN member_contributions mc 
    ON c.contribution_id = mc.contribution_id AND mc.member_id = $user_id 
    WHERE mc.status IS NULL OR mc.status != 'paid'"
);

$unpaid_result = mysqli_fetch_assoc($unpaid_query);


?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once('../partials/navbar.php'); ?>
        <?php require_once('../partials/top_navbar.php'); ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div> -->


            <div></div>
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->

            <!-- Main content  admin dashbord-->
            <div class="level">

                <?php if ($user_role == 'System Administrator') : ?>
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <a href="Members" style="text-decoration: none;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Members</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo htmlspecialchars($total_members)?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <a href="applications" style="text-decoration: none;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Loans</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo htmlspecialchars($loans)?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-landmark fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <a href="Contributions" style="text-decoration: none;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Contributions</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo htmlspecialchars($contributions)?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                    <!-- Applications Breakdown Per Sub county -->
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <!-- member growth chart -->
                        <h4 style="color: #333; font-weight: bold;" class="mt-4">📊 Member Growth Over Time</h4>
                        <div class="chart-container" width="300px" height="300px">
                            <canvas id="memberGrowthChart" width="100%" height="100%"></canvas>
                        </div>

                        <script>
                            fetch('fetch_member_growth.php')
                                .then(response => response.json())
                                .then(data => {
                                    const ctx = document.getElementById('memberGrowthChart').getContext('2d');

                                    new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: data.labels, 
                                            datasets: [{
                                                label: 'Total Members',
                                                data: data.values, 
                                                borderColor: 'blue',
                                                backgroundColor: 'rgba(0, 0, 255, 0.1)',
                                                borderWidth: 2,
                                                fill: true
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                })
                                .catch(error => console.error('Error fetching data:', error));
                        </script>

                    </div>
                    <div class="col-xl-6">
                        <!-- loan distribution chart -->
                        <h4 style="color: #333; font-weight: bold;" class="mt-4">🏦 Loan Distribution Percentages</h4>
                            
                            <div class="chart-container" width = "400px" height = "400px">
                                <canvas id="loanDistributionChart" width = "400px" height = "400px"></canvas>
                            </div>

                            <script>
                                fetch('loan_distribution.php') 
                                    .then(response => response.json())
                                    .then(data => {
                                        const ctx = document.getElementById('loanDistributionChart').getContext('2d');

                                        new Chart(ctx, {
                                            type: 'pie',
                                            data: {
                                                labels: ['Approved Loans', 'Savings', 'Repayments'],
                                                datasets: [{
                                                    label: 'Loan Distribution',
                                                    data: [data.approved_loans, data.savings, data.repayments],
                                                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56']
                                                }]
                                            }
                                        });
                                    })
                                    .catch(error => console.error('Error fetching data:', error));
                            </script>
                    </div>
              </div>

                <!-- Members Dashboard -->
                <?php elseif ($user_role == 'Member') : ?>
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <a href="savings" style="text-decoration: none;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Savings</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo "Ksh." . number_format(htmlspecialchars($amount), 2)?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-fw fa-piggy-bank fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <a href="Loans" style="text-decoration: none;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Loan Balance</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo "Ksh." . number_format(htmlspecialchars($loan), 2)?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-landmark fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <a href="Contributions" style="text-decoration: none;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Pending Contributions</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $unpaid_result['unpaid_count']?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                    <!-- Applications Breakdown Per Sub county -->
                </div>

                <div class="row">
                   <div class="col-xl-6 mt-4">
                        <h4 style="color: #333; font-weight: bold;">📊 loan Repayment Over Time</h4>
                            <div class="chart-container" width="500px" height="400px" margin="auto">
                                <canvas id="loanRepaymentChart" width="500px" height="400px"></canvas>
                            </div>

                        <script>
                            fetch('loanrepaymentchart.php') 
                            .then(response => response.json())
                            .then(chartData => {
                                const ctx = document.getElementById("loanRepaymentChart").getContext("2d");

                                new Chart(ctx, {
                                type: "line",
                                data: {
                                    labels: chartData.labels, 
                                    datasets: [{
                                        label: "Loan Repayment Progress",
                                        data: chartData.data, 
                                        borderColor: "rgba(75, 192, 192, 1)",
                                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                                        fill: true,
                                        tension: 0.4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: { display: true }
                                    },
                                    scales: {
                                        x: { title: { display: true, text: "Date" } },
                                        y: { title: { display: true, text: "Amount Paid (KSH)" }, beginAtZero: true }
                                    }
                                }
                                });
                            })
                            .catch(error => console.error("Error fetching chart data:", error));
                        </script>
                   </div>

                   <div class="col-xl-6">
                        <div class="container mt-4">
                            <h4 style="color: #333; font-weight: bold;">Recent transactions</h4>
                            <div id="transactions-list"></div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                fetch('recent_transactions.php')
                                    .then(response => response.json())
                                    .then(data => {
                                        let transactionsHTML = "";

                                        if (data.length === 0) {
                                            transactionsHTML = "<p>No recent transactions.</p>";
                                        } else {
                                            data.forEach(transaction => {
                                                let badgeClass = transaction.status.toLowerCase() === 'paid' ? 'bg-success' : 'bg-warning';

                                                transactionsHTML += `
                                                    <div class="card mb-3 shadow-sm">
                                                        <div class="card-body">
                                                            <h5 class="card-title">${transaction.title}</h5>
                                                            <p class="card-text">
                                                                <strong>Due Date:</strong> ${transaction.due_date} <br>
                                                                <strong>Amount Paid:</strong> $${transaction.amount_paid} <br>
                                                                <strong>Status:</strong> 
                                                                <span class="badge ${badgeClass}">${transaction.status}</span> <br>
                                                                <strong>Payment Date:</strong> ${new Date(transaction.payment_date).toLocaleString()}
                                                            </p>
                                                        </div>
                                                    </div>`;
                                            });
                                        }

                                        $('#transactions-list').html(transactionsHTML);
                                    })
                                    .catch(error => console.error('Error fetching transactions:', error));
                            });

                        </script>

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