<?php
require_once('../config/config.php');
if (isset($_POST['update_saving'])) {
    $savings_id = $_POST['savings_id'];
    $member_name = $_POST['member_name'];
    $member_id_no = $_POST['member_id_no'];
    $member_phone = $_POST['member_phone'];
    $reference_no = $_POST['reference_no'];
    $amount = $_POST['amount'];
    $savings_date = $_POST['savings_date'];
    $payment_method = $_POST['payment_method'];

    // Check if the expense exists
    $check_saving_sql = "SELECT savings_id FROM savings WHERE savings_id = ?";
    if ($stmt_saving = $mysqli->prepare($check_saving_sql)) {
        $stmt_saving->bind_param('i', $savings_id);
        $stmt_saving->execute();
        $stmt_saving->store_result();

        if ($stmt_saving->num_rows > 0) {
            $stmt_saving->close(); // Close after checking existence

            // Prepare the SQL statement to update the saving
            $sql = "UPDATE savings SET 
                        member_name = ?, 
                        member_id_no = ?, 
                        member_phone = ?, 
                        reference_no = ?,
                        amount = ?,
                        savings_date = ?,
                        payment_method = ?
                    WHERE savings_id = ?";

            if ($stmt_update = $mysqli->prepare($sql)) {
                $stmt_update->bind_param(
                    'siisidsi',
                    $member_name, $member_id_no, $member_phone, $reference_no, $amount, $savings_date, $payment_method, $expense_id
                );

                if ($stmt_update->execute()) {
                    $_SESSION['success'] = 'saving updated successfully.';
                    header('Location: ../views/savings');
                    exit();
                } else {
                    echo "Error updating saving: " . $stmt_update->error;
                }

                $stmt_update->close();
            } else {
                echo "Error preparing update query: " . $mysqli->error;
            }
        } else {
            echo "Error: saving not found.";
        }

        $stmt_saving->close();
    } else {
        echo "Error checking saving: " . $mysqli->error;
    }
}
?>