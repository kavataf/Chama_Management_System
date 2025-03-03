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
                    <form action="../helpers/send_email.php" method="POST">
                    <legend class="w-auto text-success">Send Email</legend>

                    <!-- Select recipient type -->
                    <div class="form-group">
                        <label for="recipient_type">Send to:</label>
                        <select id="recipient_type" name="recipient_type" class="form-control" required onchange="toggleRecipientField()">
                            <option value="single">Single Recipient</option>
                            <option value="all">All Members</option>
                        </select>
                    </div>

                    <!-- Email input (only visible for single recipient) -->
                    <div class="form-group" id="recipient_email_field">
                        <label for="recipient">Recipient Email: <span class="text-danger">*</span></label>
                        <input type="email" id="recipient" name="recipient" class="form-control" placeholder="Enter email">
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject: <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" placeholder="Enter subject" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message: <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" placeholder="Enter message" required></textarea>
                    </div>

                    <button type="submit" name="send" class="btn btn-outline-primary">Send Email</button>
                </form>

                <!-- JavaScript to Show/Hide Email Input -->
                <script>
                    function toggleRecipientField() {
                        const recipientType = document.getElementById("recipient_type").value;
                        const recipientField = document.getElementById("recipient_email_field");
                        
                        if (recipientType === "all") {
                            recipientField.style.display = "none"; // Hide email input
                            document.getElementById("recipient").removeAttribute("required");
                        } else {
                            recipientField.style.display = "block"; // Show email input
                            document.getElementById("recipient").setAttribute("required", "required");
                        }
                    }
                    
                    // Initialize on page load
                    toggleRecipientField();
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