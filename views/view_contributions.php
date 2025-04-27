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

$contribution_id = (int)$_GET['id']; 
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

// Get contribution title
$stmt = $mysqli->prepare("SELECT title FROM contributions WHERE contribution_id = ?");
$stmt->bind_param("i", $contribution_id);
$stmt->execute();
$contrib = $stmt->get_result()->fetch_assoc();
if (!$contrib) {
    die("Contribution not found.");
}


// Prepare member contributions query
$sql = "SELECT m.member_name AS member_name, c.amount_paid, c.status 
        FROM member_contributions c
        JOIN members m ON c.member_id = m.user_id 
        WHERE c.contribution_id = ?";
$params = [$contribution_id];
$types = "i";

if (!empty($filter_status)) {
    $sql .= " AND c.status = ?";
    $params[] = $filter_status;
    $types .= "s";
}

$query = $mysqli->prepare($sql);
$query->bind_param($types, ...$params);
$query->execute();
$members_query = $query->get_result();
?>


<body id="page-top">

<style>
    .no-records {
    color: #888;
    font-style: italic;
    font-size: 16px;
    padding: 20px 0;
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

            <!-- Main content  executive dashbord-->
            <div class="level">

                <?php if ($user_role == 'System Administrator') : ?>
                    <div class="container mt-4">
                            <h2><?php echo $contrib['title']; ?> - Member Contributions</h2>

                            <form method="GET">
                            <input type="hidden" name="id" value="<?php echo $contribution_id; ?>">
                                <label for="filter_status">Filter by Status:</label>
                                <select name="filter_status" id="filter_status" class="form-control col-6">
                                    <option value="">-- Select Status --</option>
                                    <option value="Paid" <?php echo ($filter_status == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                    <option value="Partially Paid" <?php echo ($filter_status == 'Partially Paid') ? 'selected' : ''; ?>>Partially Paid</option>
                                </select>
                                <button type="submit" class="btn btn-primary m-2">Filter</button>

                                <!-- Reset Filter Button -->
                                    <a href="?id=<?php echo $contribution_id; ?>" class="btn btn-secondary m-2">Reset Filter</a>
                            </form>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Member Name</th>
                                        <th>Amount Paid</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($members_query) > 0) { ?>
                                        <?php while ($row = mysqli_fetch_assoc($members_query)) { ?>
                                            <tr>
                                                <td><?php echo $row['member_name']; ?></td>
                                                <td><?php echo $row['amount_paid']; ?></td>
                                                <td><?php echo ucfirst($row['status']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center no-records text-danger">No records found.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="text-right">
                                <a href="contributions.php" class="btn btn-success m-2">Back</a>
                                <a href="../reports/pdf/" class="btn btn-info m-2">
                                    <i class="fas fa-save"></i> Generate Report
                                </a>
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
