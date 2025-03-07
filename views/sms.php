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
                <form action="../helpers/send_sms.php" method="POST">
                    <legend class="w-auto text-success">Send SMS</legend>

                    <!-- Select recipient type -->
                    <div class="form-group">
                        <label for="recipient_type">Send to:</label>
                        <select id="recipient_type" name="recipient_type" class="form-control" required onchange="togglePhoneField()">
                            <option value="single">Single Recipient</option>
                            <option value="all">All Members</option>
                        </select>
                    </div>

                    <!-- Phone number input (only for single recipient) -->
                    <div class="form-group" id="phone_number_field">
                        <label for="phone">Recipient Phone Number:</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number">
                    </div>

                    <div class="form-group">
                        <label for="message">Message: <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" placeholder="Enter message" required></textarea>
                    </div>

                    <button type="submit" name="send" class="btn btn-outline-primary">Send SMS</button>
                </form>

                <script>
                    function togglePhoneField() {
                        const recipientType = document.getElementById("recipient_type").value;
                        const phoneField = document.getElementById("phone_number_field");

                        if (recipientType === "all") {
                            phoneField.style.display = "none";
                            document.getElementById("phone").removeAttribute("required");
                        } else {
                            phoneField.style.display = "block";
                            document.getElementById("phone").setAttribute("required", "required");
                        }
                    }
    
                    togglePhoneField(); // Initialize 
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