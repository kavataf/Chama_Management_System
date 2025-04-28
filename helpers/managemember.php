<?php
require_once('../config/config.php');
if (isset($_POST['update_member'])) {
    $members_id = $_POST['user_id'];
    $member_name = $_POST['member_name'];
    $member_id_no = $_POST['member_id_no'];
    $member_phone = $_POST['member_phone'];
    $member_email = $_POST['member_email'];
    $member_gender = $_POST['member_gender'];

    // Check if the member exists
    $check_member_sql = "SELECT user_id FROM members WHERE user_id = ?";
    if ($stmt_member = $mysqli->prepare($check_member_sql)) {
        $stmt_member->bind_param('i', $members_id);
        $stmt_member->execute();
        $stmt_member->store_result();

        if ($stmt_member->num_rows > 0) {
            $stmt_member->close(); 

            // Prepare the SQL statement to update the member
            $sql = "UPDATE members SET 
                        member_name = ?, 
                        member_id_no = ?, 
                        member_phone = ?, 
                        member_email = ?, 
                        member_gender = ? 
                    WHERE user_id = ?";

            if ($stmt_update = $mysqli->prepare($sql)) {
                $stmt_update->bind_param(
                    'sssssi',
                    $member_name, $member_id_no, $member_phone, $member_email, $member_gender, $members_id
                );

                if ($stmt_update->execute()) {
                    echo "<script>
                    alert('member updated successfully.');
                    window.location.href = '../views/members';
                  </script>";
                     exit;
                } else {
                    echo "Error updating member: " . $stmt_update->error;
                }

                $stmt_update->close();
            } else {
                echo "Error preparing update query: " . $mysqli->error;
            }
        } else {
            echo "<script>
                    alert('Member not found.');
                    window.location.href = '../views/members';
                  </script>";
            exit;
        }
        

        $stmt_member->close();
    } else {
        echo "Error checking member: " . $mysqli->error;
    }
}

?>
