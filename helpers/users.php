<?php 
require_once("../config/config.php");
require_once("../helpers/auth.php");

// Function to sanitize input
function sanitize_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* Update Users */
    if (isset($_POST['Update_User'])) {
        $user_names = mysqli_real_escape_string($mysqli, sanitize_input($_POST['user_name']));
        $user_email = mysqli_real_escape_string($mysqli, sanitize_input($_POST['user_email']));
        $user_phone_number = mysqli_real_escape_string($mysqli, sanitize_input($_POST['user_phone']));
        $user_id_no = mysqli_real_escape_string($mysqli, sanitize_input($_POST['user_id_no']));
        $user_id = mysqli_real_escape_string($mysqli, sanitize_input($_POST['user_id']));

        /* Persist */
        $update_query = "UPDATE users SET user_name = '{$user_names}', user_email = '{$user_email}', user_phone = '{$user_phone_number}', user_id_no = '{$user_id_no}' WHERE user_id = '{$user_id}'";
        if (mysqli_query($mysqli, $update_query)) {
            echo "<script>alert('User updated!');</script>";
            // $success = "User updated";
        } else {
            $err = "Failed, please try again. Error: " . mysqli_error($mysqli);
        }
    }

    /* Update Passwords */
    if (isset($_POST['UpdatePasswords'])) {
        $user_id = mysqli_real_escape_string($mysqli, $_SESSION['user_id']);
        $old_password = mysqli_real_escape_string($mysqli, sanitize_input($_POST['old_password']));
        $new_password = mysqli_real_escape_string($mysqli, sanitize_input($_POST['new_password']));
        $confirm_password = mysqli_real_escape_string($mysqli, sanitize_input($_POST['confirm_password']));

        /* Check If Old Password Match */
        $sql = "SELECT * FROM users WHERE user_id = '{$user_id}'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (!password_verify($old_password, $row['user_password'])) {
                $err = "Please enter correct old password";
            } elseif ($new_password != $confirm_password) {
                $err = "Confirmation password does not match";
            } else {
                $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $update_query = "UPDATE users SET user_password = '{$new_password_hashed}' WHERE user_id = '{$user_id}'";
                if (mysqli_query($mysqli, $update_query)) {
                    echo "<script>alert('Password updated!');</script>";
                    // $success = "Password updated";
                } else {
                    $err = "Failed, please try again. Error: " . mysqli_error($mysqli);
                }
            }
        } else {
            $err = "User not found.";
        }
    }
}
?>