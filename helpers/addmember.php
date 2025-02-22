<?php 
require_once('../config/config.php'); 

if (isset($_POST['member_details'])) {
    $member_name = trim($_POST['member_name']);
    $member_gender = trim($_POST['member_gender']);
    $member_id_no = trim($_POST['member_id_no']);
    $member_email = trim($_POST['member_email']);
    $member_phone = trim($_POST['member_phone']);
    $member_password = bin2hex(random_bytes(8)); // Generate a random password
    $hashed_password = password_hash($member_password, PASSWORD_DEFAULT);
    $access_level = 'Member';

    // Start transaction
    $mysqli->begin_transaction();

    try {
        // Prepare to insert into users table
        $stmt = $mysqli->prepare("INSERT INTO users (user_name, user_gender, user_id_no, 
            user_email, user_phone, user_access_level, user_password, user_unhashed_password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $member_name, $member_gender, $member_id_no, $member_email, 
            $member_phone, $access_level, $hashed_password, $member_password);

        if ($stmt->execute()) {
            $user_id = $mysqli->insert_id; // Get the generated user ID
            $_SESSION['user_id'] = $user_id;

            // Prepare to insert into members table
            $stmt2 = $mysqli->prepare("INSERT INTO members (user_id, member_name, member_gender, 
                member_email, member_phone, member_id_no) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("isssss", $user_id, $member_name, $member_gender, 
                $member_email, $member_phone, $member_id_no);

            if ($stmt2->execute()) {
                // Commit transaction after both inserts are successful
                $mysqli->commit();

                $_SESSION['success'] = "Member added successfully. Password has been sent to the email.";
                header("location: members.php");
                exit;
            } else {
                throw new Exception("Failed to insert into members table: " . $stmt2->error);
            }
        } else {
            throw new Exception("Failed to insert into users table: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $mysqli->rollback();
        $_SESSION['error'] = "Something went wrong, please try again. " . $e->getMessage();
        header("location: members.php"); 
        exit;
    }
}
?>
