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

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Contribution ID.");
}

$contribution_id = (int)$_GET['id']; // Ensure ID is an integer

// Fetch Contribution Details
$contribution_query = mysqli_query($mysqli, "SELECT * FROM contributions WHERE contribution_id = $contribution_id");
if (!$contribution_query || mysqli_num_rows($contribution_query) == 0) {
    die("Contribution not found.");
}
$contrib = mysqli_fetch_assoc($contribution_query);

// Fetch Member Contributions
$members_query = mysqli_query($mysqli, "SELECT mc.member_id, m.member_name, mc.amount_paid, mc.status 
                                      FROM member_contributions mc 
                                      JOIN members m ON mc.member_id = m.user_id 
                                      WHERE mc.contribution_id = $contribution_id");

if (!$members_query) {
    die("Error fetching member contributions: " . mysqli_error($mysqli));
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
                    <div class="container mt-4">
                        <h2><?php echo $contrib['title']; ?> - Member Contributions</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Amount Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($members_query)) { ?>
                                    <tr>
                                        <td><?php echo $row['member_name']; ?></td>
                                        <td><?php echo $row['amount_paid']; ?></td>
                                        <td><?php echo ucfirst($row['status']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <a href="contributions.php" class="btn btn-success">Back</a>
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
