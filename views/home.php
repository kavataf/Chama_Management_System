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

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT COUNT(*) AS total_members FROM members";

$result = $mysqli -> query($sql);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $total_members = $row['total_members'];
    }
}

$user_id = $_SESSION['user_id'];

$sql2 = "SELECT SUM(amount_saved) AS amount, user_id
FROM savings 
WHERE user_id = ?";

$stmt = $mysqli -> prepare($sql2);
$stmt -> bind_param("i", $user_id);
$stmt -> execute();
$stmt -> bind_result($amount, $fetched_user_id);
$stmt -> fetch();
$stmt -> close();

$sql3 = "SELECT SUM(total_payable) AS loan, user_id 
FROM applications WHERE user_id = ?";

$stmt = $mysqli -> prepare($sql3);
$stmt -> bind_param("i", $user_id);
$stmt -> execute();
$stmt -> bind_result($loan, $fetched_user_id);
$stmt -> fetch();
$stmt -> close();

$repayment = "SELECT SUM(amount_paid) AS Amount_repaid FROM repayments WHERE user_id = ?";

$stmt = $mysqli -> prepare($repayment);
$stmt -> bind_param("i", $user_id);
$stmt -> execute();
$stmt -> bind_result($Amount_repaid);
$stmt -> fetch();
$stmt -> close();

// loan balance
$Loan_balance = $loan - $Amount_repaid;

$sql4 = "SELECT COUNT(*) AS loan_applications FROM applications WHERE loan_status = 'pending'";

$result = $mysqli -> query($sql4);
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
        $loannum = $row['loan_applications'];
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

// Fetch active loans
$sql6 = "SELECT loan_name, total_payable, application_date, loan_status, loan_amount 
        FROM applications 
        WHERE loan_status = 'approved'
        ORDER BY application_date DESC";
$result = $mysqli->query($sql6);

// Prepare data
$loans = [];
$totalPayable = 0;
$totalLoanAmount = 0;

while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
    $totalPayable += $row['total_payable'];
    $totalLoanAmount += $row['loan_amount'];
}

$profit = $totalPayable - $totalLoanAmount;
$profitPercent = $totalLoanAmount > 0 ? ($profit / $totalLoanAmount) * 100 : 0;

// Fetch pending contributions
$sql7 = "SELECT * 
FROM contributions 
WHERE due_date >= CURDATE()
ORDER BY due_date ASC";
$result = $mysqli->query($sql7);

$pending_contributions = [];
if($result -> num_rows > 0){
    while ($row = $result -> fetch_assoc()){
        $pending_contributions[] = $row;
    }
}

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body id="page-top">
    <style>
       
        .active-loan-scroll::-webkit-scrollbar {
        width: 6px;
        }

        .active-loan-scroll::-webkit-scrollbar-track {
        background: transparent;
        }

        .active-loan-scroll::-webkit-scrollbar-thumb {
        background-color: #d1d5db; /* Soft gray */
        border-radius: 4px;
        }

        .active-loan-scroll:hover::-webkit-scrollbar-thumb {
        background-color: #a0aec0; /* Darker on hover */
        }

        /* Firefox scrollbar */
        .active-loan-scroll {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db transparent;
        }

    </style>

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
                                                    <span class="info-box-text text-info">Total Members</span>
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
                                                    <span class="info-box-text text-warning">Pending Loan applications</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo htmlspecialchars($loannum)?></div>
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
                                                    <span class="info-box-text text-info">Total contributions</span>
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
                </div>
                <div class="row g-3">
                    <!-- loan applications -->
                    <div class="col-md-4">
                        <div class="card p-3">
                            <div class="card-title">Loan Applications by Status</div>
                            <div style="height: 250px;">
                                <canvas id="loanChart" width="100%" height="90%"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Member Growth Chart Card -->
                    <div class="col-md-4">
                        <div class="card p-3">
                        <div class="card-title">📊 Member Growth Over Time</div>
                        <div style="height: 250px;">
                            <canvas id="memberGrowthChart" width="100%" height="90%"></canvas>
                        </div>
                        </div>
                    </div>

                    <!-- Loan Distribution Chart Card -->
                    <div class="col-md-4">
                        <div class="card p-3">
                        <div class="card-title">🏦 Loan Distribution</div>
                        <div style="height: 250px;">
                            <canvas id="loanDistributionChart" width="100%" height="90%"></canvas>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-1 text-success">💼 Active Loans</h5>
                                    <small class="text-muted">Profit to be realized</small>
                                </div>
                                <div class="text-success fw-bold">
                                    <i class="fa fa-arrow-up"></i>
                                    KSh <?php echo number_format($profit); ?> 
                                    <small class="text-success">(<?php echo number_format($profitPercent, 1); ?>%)</small>
                                </div>
                                </div>
                                <div class="active-loan-scroll" style="max-height: 280px; overflow: auto;">
                                    <table class="table table-borderless align-middle small">
                                    <thead>
                                        <tr class="text-muted small text-uppercase">
                                        <th style="width: 10px;">S/N</th>
                                        <th style="width: 50%;">Loan Name</th>
                                        <th>Total Payable</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($loans as $loan): ?>
                                        <tr>
                                        <td><?php echo sprintf('%02d', $i++); ?></td>
                                        <td><?php echo htmlspecialchars($loan['loan_name']); ?></td>
                                        <td>KSh <?php echo number_format($loan['total_payable']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($loan['application_date'])); ?></td>
                                        <td>
                                            <span class="text-success"><?php echo ucfirst($loan['loan_status']); ?></span>
                                        </td>
                                        </tr>
                                        <tr><td colspan="5"><hr class="my-1"></td></tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-1 text-warning">💰 Pending Contributions</h5>
                                    </div>
                                </div>
                                <div class="active-loan-scroll" style="max-height: 280px; overflow: auto;">
                                    <table class="table table-borderless align-middle small">
                                    <thead>
                                        <tr class="text-muted small text-uppercase">
                                        <th>S/N</th>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($pending_contributions as $pending_contribution): ?>
                                        <tr>
                                        <td><?php echo sprintf('%02d', $i++); ?></td>
                                        <td><?php echo htmlspecialchars($pending_contribution['title']); ?></td>
                                        <td>KSh <?php echo number_format($pending_contribution['amount']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($pending_contribution['due_date'])); ?></td>
                                        </tr>
                                        <tr><td colspan="5"><hr class="my-1"></td></tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Scripts -->
                    <script>
                    // Member Growth Chart
                    fetch('fetch_member_growth.php')
                        .then(response => response.json())
                        .then(data => {
                        const ctx = document.getElementById('memberGrowthChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Joined Members',
                                data: data.values,
                                borderColor: 'blue',
                                backgroundColor: 'rgba(0, 0, 255, 0.1)',
                                borderWidth: 2,
                                fill: true
                            }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                title: {
                                    display: true,
                                    text: (ctx) => 'Point Style: ' + ctx.chart.data.datasets[0].pointStyle,
                                }
                                }
                            }
                        });
                        })
                        .catch(error => console.error('Error fetching data:', error));

                    // Loan Distribution Chart
                    fetch('loan_distribution.php')
                        .then(response => response.json())
                        .then(data => {
                        const ctx = document.getElementById('loanDistributionChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'polarArea',
                            data: {
                            labels: ['Approved Loans', 'Savings', 'Repayments'],
                            datasets: [{
                                label: 'Loan Distribution',
                                data: [data.approved_loans, data.savings, data.repayments],
                                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56']
                            }]
                            },
                            options: {
                            responsive: true,
                            scales: {
                            r: {
                                pointLabels: {
                                display: true,
                                centerPointLabels: true,
                                font: {
                                    size: 18
                                }
                                }
                            }
                            },
                            plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                            }
                            }
                        },
                        });
                        })
                        .catch(error => console.error('Error fetching data:', error));
                    </script>

                    <script>
                        fetch('loanapplications.php')
                        .then(response => response.json())
                        .then(data => {
                            // Check if there's a message indicating no data
                            if (data.message) {
                            document.getElementById("loanRepaymentChart").replaceWith(
                                `<p class="text-muted">${data.message}</p>`
                            );
                            return; 
                            }

                            // If there's data, process it and create the chart
                            const labels = data.map(item => item.loan_status);
                            const values = data.map(item => item.total);

                            const ctx = document.getElementById('loanChart').getContext('2d');
                            new Chart(ctx, {
                            type: 'doughnut',  
                            data: {
                                labels: labels,
                                datasets: [{
                                label: 'Applications',
                                data: values,
                                backgroundColor: ['#4CAF50', '#FFC107', '#F44336'], 
                                borderColor: ['#4CAF50', '#FFC107', '#F44336'], 
                                borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                legend: { position: 'top' },
                                title: {
                                    display: true,
                                    text: 'Loan Applications by Status'
                                }
                                },
                                cutoutPercentage: 70,  
                                rotation: Math.PI * 1.5  
                            }
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching chart data:', error);
                        });
                    </script>


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
                                                    <span class="info-box-text text-info">Savings</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo "Ksh." . (is_numeric($amount) ? number_format((float)$amount, 2) : "0.00")?></div>
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
                                                    <span class="info-box-text text-warning">Loan Balance</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo "Ksh." . (is_numeric($Loan_balance) ? number_format((float)$Loan_balance, 2) : "0.00")?></div>
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
                                                    <span class="info-box-text text-danger">Pending Contributions</span>
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

                <div class="row g-3">
                    <!-- Loan repayment Chart Card -->
                    <div class="col-md-6">
                        <div class="card p-3">
                        <div class="card-title">💸 Loan Repayment Progress</div>
                        <div style="height: 300px;">
                            <canvas id="loanRepaymentChart" width="500px" height="250px"></canvas>
                        </div>
                        </div>
                    </div>

                    <!-- Recent transactions Card -->
                    <div class="col-md-6">
                        <div class="card p-3">
                        <div class="card-title">🏦 Recent transactions</div>
                        <div style="height: 250px; overflow: auto;">
                            <div id="transactions-list"></div>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- repayment -->
                <script>
                    fetch('loanrepaymentchart.php') 
                        .then(response => response.json())
                        .then(chartData => {
                            if (chartData.message) {
                                document.getElementById("loanRepaymentChart").replaceWith(
                                    `<p class="text-muted">${chartData.message}</p>`
                                );
                                return;
                                }
                        const ctx = document.getElementById("loanRepaymentChart").getContext("2d");

                        new Chart(ctx, {
                            type: "bar",
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
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: true }
                            },
                            scales: {
                                x: {
                                title: {
                                    display: true,
                                    text: "Date"
                                }
                                },
                                y: {
                                title: {
                                    display: true,
                                    text: "Amount Paid (KSH)"
                                },
                                beginAtZero: true
                                }
                            }
                            }
                        });
                        })
                        .catch(error => console.error("Error fetching chart data:", error));
                </script>

                    <!-- transaction list -->
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