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
                    <form action="../helpers/send_email.php" method="POST" onsubmit="return validateForm()">
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
                            <input type="email" id="recipient" name="recipient" class="form-control" placeholder="Enter email" required>
                            <span id="email_error" class="text-danger" style="display:none;">Please enter a valid email address.</span>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject: <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="Enter subject" required pattern="[A-Za-z0-9 ]+" title="Only letters, numbers, and spaces are allowed.">
                            <span id="subject_error" class="text-danger" style="display:none;">Subject can only contain letters, numbers, and spaces.</span>
                        </div>

                        <div class="form-group">
                            <label for="message">Message: <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" placeholder="Enter message" required pattern="[A-Za-z0-9 ]+" title="Only letters, numbers, and spaces are allowed."></textarea>
                            <span id="message_error" class="text-danger" style="display:none;">Message can only contain letters, numbers, and spaces.</span>
                        </div>

                        <button type="submit" name="send" class="btn btn-outline-primary">Send Email</button>
                    </form>


                <!-- JavaScript to Show/Hide Email Input -->
                <script>
                    function toggleRecipientField() {
                        var recipientType = document.getElementById("recipient_type").value;
                        var recipientEmailField = document.getElementById("recipient_email_field");

                        if (recipientType === "single") {
                            recipientEmailField.style.display = "block";
                            document.getElementById("recipient").setAttribute("required", "required");
                        } else {
                            recipientEmailField.style.display = "none";
                            document.getElementById("recipient").removeAttribute("required");
                        }
                    }

                    function validateForm() {
                        let valid = true;

                        // Clear any previous error messages
                        document.getElementById("subject_error").style.display = "none";
                        document.getElementById("message_error").style.display = "none";
                        document.getElementById("email_error").style.display = "none";

                        // Check if subject and message fields contain only letters, numbers, and spaces
                        var subject = document.querySelector('input[name="subject"]').value;
                        var message = document.querySelector('textarea[name="message"]').value;

                        var textPattern = /^[A-Za-z0-9 ]+$/;

                        if (!textPattern.test(subject)) {
                            document.getElementById("subject_error").style.display = "block";
                            valid = false;
                        }

                        if (!textPattern.test(message)) {
                            document.getElementById("message_error").style.display = "block";
                            valid = false;
                        }

                        // If "Single Recipient" is selected, check if the email is provided and valid
                        if (document.getElementById("recipient_type").value === "single") {
                            var recipientEmail = document.getElementById("recipient").value;
                            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                            if (!emailPattern.test(recipientEmail)) {
                                document.getElementById("email_error").style.display = "block";
                                valid = false;
                            }
                        }

                        return valid; // Only allow form submission if all validations pass
                    }

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