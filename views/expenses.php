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
$sql = "SELECT * FROM expenses ";
$result = $mysqli -> query($sql);
$expenses = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $expenses[] = $row;
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

         
            <div></div>
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->

            <!-- Main content  executive dashbord-->
            <div class="level">

                <?php if ($user_role == 'System Administrator') : ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Expenses</h5>
                                        <a href="#addexpense" data-toggle="modal" style="text-decoration:none;" class="d-none d-sm-inline-block shadow-sm"><button type="button" class="btn btn-block btn-primary">Add expense</button></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <!-- breakdown table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">No</th>
                                                        <th>Vendor</th>
                                                        <th>Expense</th>
                                                        <th>REF No</th>
                                                        <th>Expense Amount</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($expenses)) : ?>
                                                    <?php foreach ($expenses as $key => $expense) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($expense['vendor_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($expense['expense_type']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($expense['reference_no']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($expense['expense_amount']); ?>
                                                        </td>
                                                        <td class="sorting_1" style="width: 15%;">
                                                            <a data-toggle="modal" href="#updateexpense"
                                                                class="badge badge-primary"
                                                                data-id="<?php echo $expense['expense_id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($expense['vendor_name']); ?>"
                                                                data-type="<?php echo htmlspecialchars($expense['expense_type']); ?>"
                                                                data-ref="<?php echo htmlspecialchars($expense['reference_no']); ?>"
                                                                data-amount="<?php echo htmlspecialchars($expense['expense_amount']); ?>"
                                                                onclick="setUpdateModalData(this)">
                                                                <i class="fas fa-edit"></i> Update
                                                            </a>
                                                            <a class="badge badge-danger" 
                                                            data-id="<?php echo htmlspecialchars($expense['expense_id']);?>" href="#deleteModal"
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
                                                    <?php require_once('../modals/add_expense.php'); ?>
                                                    <?php require_once('../modals/manage_expense.php'); ?>
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