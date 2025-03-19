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
                    <h2>Add Contribution</h2>
                    
                    <!-- Add Contribution Form -->
                        <form class="needs-validation" method="post" enctype="multipart/form-data">
                                <fieldset class="border p-2 border-success">
                                    <legend class="w-auto text-success">Manage Contributions</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="">Title<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Title" required name="title" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Amount<span class="text-danger">*</span></label>
                                            <input type="number" name="amount" class="form-control" placeholder="Amount" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="">Due Date<span class="text-danger">*</span></label>
                                            <input type="date" name="due_date" class="form-control" required>
                                        </div>
                                        
                                    </div>
                                </fieldset>
                                <div class="modal-footer">
                                    <div class="text-right">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="add_contribution" class="btn btn-outline-primary">
                                            <i class="fas fa-save"></i> Save
                                        </button>
                                    </div>
                                </div>


                            </form>
                    
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