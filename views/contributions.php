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
// Insert new contribution
if (isset($_POST['add_contribution'])) {
    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];
    
    $query = "INSERT INTO contributions (title, amount, due_date) VALUES ('$title', '$amount', '$due_date')";
    if (mysqli_query($mysqli, $query)) {
        echo "<script>alert('Contribution Added Successfully!');</script>";
    } else {
        echo "<script>alert('Error adding contribution.');</script>";
    }
}

// Fetch contributions
$contributions = mysqli_query($mysqli, "SELECT * FROM contributions ORDER BY due_date DESC");

// Fetch member's contributions (paid)
$paid_contributions_query = mysqli_query($mysqli, "SELECT contribution_id, amount_paid, status
FROM member_contributions WHERE member_id = $user_id");

$paid_contributions = [];
while ($row = mysqli_fetch_assoc($paid_contributions_query)) {
    $paid_contributions[$row['contribution_id']] = [
        'amount_paid' => $row['amount_paid'],
        'status' => $row['status'] 
    ];
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
                    <div class="container">
                            <div class="row mb-4">
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('addcontribution')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">Add Contribution</span>
                                    </a>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('viewcontribution')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">View Contribution</span>
                                    </a>
                                </div>
                                
                                <!-- <div class="col-xl-4 col-md-4 mb-4">
                                    <form method="post" action="notifications.php">
                                        <button class="btn btn-primary" type="submit" name="send_notifications">Send Contribution Notifications</button>
                                    </form>
                                </div> -->
                            </div>

                        <div class="container mt-4">
                         <div class="row">
                          <div class="col-xl-12">
                            <div id="addcontribution" class="content-section">
                             <h2>Add Contribution</h2>
                            
                             <!-- Add Contribution Form -->
                             <form class="needs-validation" method="post" enctype="multipart/form-data" novalidate>
                                <fieldset class="border p-2 border-success">
                                    <legend class="w-auto text-success">Manage Contributions</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="title">Title<span class="text-danger">*</span></label>
                                            <input type="text" id="title" name="title" class="form-control" placeholder="Title" required pattern="^[A-Za-z\s]+$">
                                            <div class="invalid-feedback">
                                                Please provide a valid title (only letters and spaces allowed).
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="amount">Amount<span class="text-danger">*</span></label>
                                            <input type="number" id="amount" name="amount" class="form-control" placeholder="Amount" required min="1" step="any">
                                            <div class="invalid-feedback">
                                                Please enter a valid amount greater than zero.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="due_date">Due Date<span class="text-danger">*</span></label>
                                            <input type="date" id="due_date" name="due_date" class="form-control" required>
                                            <div class="invalid-feedback">
                                                Please select a due date.
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="modal-footer">
                                    <div class="text-right">
                                        <button type="submit" name="add_contribution" class="btn btn-outline-primary">
                                            <i class="fas fa-save"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <script>
                                (function () {
                                    'use strict'
                                    var forms = document.querySelectorAll('.needs-validation')

                                    Array.prototype.slice.call(forms)
                                        .forEach(function (form) {
                                            form.addEventListener('submit', function (event) {
                                                if (!form.checkValidity()) {
                                                    event.preventDefault()
                                                    event.stopPropagation()
                                                }
                                                form.classList.add('was-validated')
                                            }, false)
                                        })
                                })()
                            </script>

                            </div>
                          </div>
                         </div>
                         <div class="row">
                          <div class="col-xl-12">
                            <div id="viewcontribution" class="content-section card card-yellow card-outline" style="display: none;">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">All Contributions</h5>
                                        <div class="text-right">
                                            <a href="../reports/pdf/contributions.php" class="btn btn-info m-2">
                                                <i class="fas fa-file-pdf"></i> Generate Report
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Contributions Table -->
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($contributions)) { ?>
                                                <tr>
                                                    <td><?php echo $row['title']; ?></td>
                                                    <td><?php echo $row['amount']; ?></td>
                                                    <td><?php echo $row['due_date']; ?></td>
                                                    <td>
                                                        <a href="view_contributions.php?id=<?php echo $row['contribution_id']; ?>" class="btn btn-success">View</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
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

                <div class="container mt-4">
                            <div class="row mb-4">
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('pendingcontribution')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">Pending Contributions</span>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('paidcontribution')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">Paid Contribution</span>
                                    </a>
                                </div>
                            </div>

                        <div class="container mt-4">
                         <div class="row">
                          <div class="col-xl-12">
                            <div id="pendingcontribution" class="content-section">
                              <!-- Display Pending Contributions -->
                              <h4 class="mt-4">Pending Contributions</h4>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($contribution = mysqli_fetch_assoc($contributions)) { 
                                            $contribution_id = $contribution['contribution_id'];
                                            $status = isset($paid_contributions[$contribution_id]) ? $paid_contributions[$contribution_id]['status'] : null;

                                            if ($status !== 'Paid') { 
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($contribution['title']); ?></td>
                                                <td><?php echo number_format($contribution['amount'], 2); ?></td>
                                                <td><?php echo htmlspecialchars($contribution['due_date']); ?></td>
                                                <td>
                                                    <?php 
                                                    if ($status === 'Partially Paid') {
                                                        echo '<span class="badge bg-warning text-dark">Partially Paid</span>';
                                                    } else {
                                                        echo '<span class="badge bg-danger text-white">Pending</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="pay_contribution.php?id=<?php echo $contribution_id; ?>" class="btn btn-success btn-sm">
                                                        <?php echo ($status === 'partially paid') ? 'Complete Payment' : 'Pay Now'; ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>

                            </div>
                          </div>
                         </div>
                         <div class="row">
                          <div class="col-xl-12">
                            <div id="paidcontribution" class="content-section" style="display: none;">
                            <!-- Display Already Contributed -->
                            <h4 class="mt-4">Already Contributed</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Amount Paid</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php mysqli_data_seek($contributions, 0); // Reset pointer ?>
                                    <?php while ($contribution = mysqli_fetch_assoc($contributions)) { 
                                        if (isset($paid_contributions[$contribution['contribution_id']])) { // Paid
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($contribution['title']); ?></td>
                                            <td><?php echo number_format($paid_contributions[$contribution['contribution_id']]['amount_paid'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($paid_contributions[$contribution['contribution_id']]['status']); ?></td>
                                            <td><?php echo $contribution['due_date']; ?></td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                            </div>
                           </div>
                          </div>
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