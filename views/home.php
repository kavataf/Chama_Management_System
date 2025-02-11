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
                                                <?php  echo htmlspecialchars($total_members)?></div>
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
                                <a href="Loans" style="text-decoration: none;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Loans</span>
                                                </div>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php ?></div>
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
                                                <?php ?></div>
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
                <!-- member growth chart -->
                <h3 style="color: #333; font-weight: bold;">📊 Member Growth Over Time</h3>
                <div class="chart-container" width="600px" height="400px">
                    <canvas id="memberGrowthChart" width="100%" height="400"></canvas>
                </div>

                <!-- <script>
                    // Fetch member growth data from PHP
                    fetch('fetch_member_growth.php')
                        .then(response => response.json())
                        .then(data => {
                            const ctx = document.getElementById('memberGrowthChart').getContext('2d');

                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: data.labels, // Months/Years
                                    datasets: [{
                                        label: 'Total Members',
                                        data: data.values, // Total members count
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
                </script> -->


                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const ctx = document.getElementById('memberGrowthChart').getContext('2d');

                        // Sample data (replace this with data from your database)
                        const labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                        const membersData = [20, 45, 60, 80, 95, 120, 150, 190, 220, 250, 280, 320];

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Total Members',
                                    data: membersData,
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 2,
                                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                    pointRadius: 5,
                                    tension: 0.3 // Smooth curve effect
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        labels: {
                                            color: '#333',
                                            font: {
                                                size: 14
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(200, 200, 200, 0.2)'
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

                <!-- loan distribution chart -->
                <h3 style="color: #333; font-weight: bold;">🏦 Loan Distribution Percentages</h3>
                    
                    <div class="chart-container" width = "600px" height = "500px">
                        <canvas id="loanDistributionChart" width = "600px" height = "500px"></canvas>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const ctx = document.getElementById('loanDistributionChart').getContext('2d');

                            // Sample Data (Replace with real database values)
                            const loanTypes = ["Personal Loan", "Business Loan", "Education Loan", "Emergency Loan"];
                            const loanAmounts = [40, 25, 20, 15]; // Percentages of total loans

                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: loanTypes,
                                    datasets: [{
                                        label: 'Loan Distribution (%)',
                                        data: loanAmounts,
                                        backgroundColor: [
                                            'rgba(54, 162, 235, 0.7)',  // Blue
                                            'rgba(255, 99, 132, 0.7)',  // Red
                                            'rgba(255, 206, 86, 0.7)',  // Yellow
                                            'rgba(75, 192, 192, 0.7)'   // Green
                                        ],
                                        borderColor: [
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: false, 
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                color: '#333',
                                                font: {
                                                    size: 14
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>
              
              <div style="width: 80%; margin: 0 auto;">
                    <canvas id="defaultersChart"></canvas>
              </div>

                <script>
                    // PHP variables injected into JavaScript
                    var memberNames = <?php echo json_encode($member_names); ?>;
                    var overdueAmounts = <?php echo json_encode($overdue_amounts); ?>;

                    var ctx = document.getElementById('defaultersChart').getContext('2d');
                    var defaultersChart = new Chart(ctx, {
                        type: 'bar',  // Chart type (Bar Chart)
                        data: {
                            labels: memberNames,  // X-axis labels (Member Names)
                            datasets: [{
                                label: 'Overdue Amounts',
                                data: overdueAmounts,  // Y-axis data (Overdue Amounts)
                                backgroundColor: 'rgba(255, 99, 132, 0.2)', // Bar color
                                borderColor: 'rgba(255, 99, 132, 1)', // Border color
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '₦' + value; // Format numbers as currency
                                        }
                                    }
                                }
                            },
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false  // Hides the legend
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return '₦' + tooltipItem.raw; // Display currency in tooltip
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>

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