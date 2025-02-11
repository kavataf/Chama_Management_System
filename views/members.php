<?php
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../partials/head.php');
require_once('../helpers/addmember.php'); 
$user_role = $_SESSION['user_access_level'];

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// fetch
$sql = "SELECT * FROM users WHERE user_access_level = 'Member'";
$result = $mysqli -> query($sql);
$members = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $members[] = $row;
    }
}

?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once('../partials/navbar.php'); ?>
        <?php require_once('../partials/top_navbar.php'); ?>
        <!-- End of Topbar -->

        <!-- Begin Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container">
                    <?php if ($user_role == 'System Administrator') : ?>
                    <div class="row">
                        <!-- Applications Breakdown Per Sub county -->
                        <div class="col-lg-12">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Members</h5>
                                        <a href="#addmember" data-toggle="modal" style="text-decoration:none;" class="d-none d-sm-inline-block shadow-sm"><button type="button" class="btn btn-block btn-primary">Add New Member</button></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <!-- Application breakdown table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">No</th>
                                                        <th>Name</th>
                                                        <th>Gender</th>
                                                        <th>ID No</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($members)) : ?>
                                                    <?php foreach ($members as $key => $member) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($member['user_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($member['user_gender']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($member['user_id_no']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($member['user_email']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($member['user_phone']); ?>
                                                        </td>

                                                        

                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <tr>
                                                        <td colspan="8">No members found</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php require_once('../modals/add_member.php'); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div><!-- /.container-fluid -->
                <!-- End of Main Content -->
            </div>

            <!-- Footer -->
            <?php require_once('../partials/footer.php'); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php require_once('../partials/scripts.php'); ?>

    <script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
    </script>

</body>


</html>