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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Contribution ID.");
}

$contribution_id = (int) $_GET['id'];

// Fetch contribution details
$contribution_query = mysqli_query($mysqli, "SELECT * FROM contributions 
WHERE contribution_id = $contribution_id");
if (!$contribution_query || mysqli_num_rows($contribution_query) == 0) {
    die("Contribution not found.");
}
$contribution = mysqli_fetch_assoc($contribution_query);
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
        <div class="container mt-4">
            <h2>Pay Contribution: <?php echo htmlspecialchars($contribution['title']); ?></h2>
            <p>Amount Required: <strong><?php echo number_format($contribution['amount'], 2); ?></strong></p>

            <form action="../helpers/process_payment.php" method="POST">
                <input type="hidden" name="contribution_id" value="<?php echo $contribution_id; ?>">
                <input type="hidden" name="member_id" value="<?php echo $user_id; ?>">

                <div class="mb-3">
                    <label for="amount" class="form-label">Enter Amount:</label>
                    <input type="number" name="amount_paid" class="form-control" required min="1">
                </div>

                <button type="submit" name="pay_contribution"class="btn btn-success">Make Payment</button>
                <a href="contributions.php" class="btn btn-secondary">Cancel</a>
                
            </form>
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
</body>
</html>
