<?php
require_once('../config/config.php');
//  update expense
if (isset($_POST['update_expense'])) {
    $expense_id = $_POST['expense_id'];
    $vendor_name = $_POST['vendor_name'];
    $expense_type = $_POST['expense_type'];
    $reference_no = $_POST['reference_no'];
    $expense_amount = $_POST['expense_amount'];

    // Check if the expense exists
    $check_expense_sql = "SELECT expense_id FROM expenses WHERE expense_id = ?";
    if ($stmt_expense = $mysqli->prepare($check_expense_sql)) {
        $stmt_expense->bind_param('i', $expense_id);
        $stmt_expense->execute();
        $stmt_expense->store_result();

        if ($stmt_expense->num_rows > 0) {
            $stmt_expense->close(); 

            // Prepare the SQL statement to update the expense
            $sql = "UPDATE expenses SET 
                        vendor_name = ?, 
                        expense_type = ?, 
                        reference_no = ?, 
                        expense_amount = ?
                    WHERE expense_id = ?";

            if ($stmt_update = $mysqli->prepare($sql)) {
                $stmt_update->bind_param(
                    'ssssi',
                    $vendor_name, $expense_type, $reference_no, $expense_amount, $expense_id
                );

                if ($stmt_update->execute()) {
                    $_SESSION['success'] = 'expense updated successfully.';
                    header('Location: ../views/expenses');
                    exit();
                } else {
                    echo "Error updating expense: " . $stmt_update->error;
                }

                $stmt_update->close();
            } else {
                echo "Error preparing update query: " . $mysqli->error;
            }
        } else {
            echo "Error: expense not found.";
        }

        $stmt_expense->close();
    } else {
        echo "Error checking expense: " . $mysqli->error;
    }
}

// update saving
if (isset($_POST['update_saving'])) {
    $savings_id = $_POST['savings_id'];
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
            $stmt_saving->close(); 

            // Prepare the SQL statement to update the saving
            $sql = "UPDATE savings SET 
                        reference_no = ?,
                        amount = ?,
                        savings_date = ?,
                        payment_method = ?
                    WHERE savings_id = ?";

            if ($stmt_update = $mysqli->prepare($sql)) {
                $stmt_update->bind_param(
                    'ssssi',
                    $reference_no, $amount, $savings_date, $payment_method, $savings_id
                );

                if ($stmt_update->execute()) {
                    $_SESSION['success'] = 'saving updated successfully.';
                    header('Location: ../views/savings');
                    exit();
                } else {
                    $_SESSION['error'] = "Error updating saving: " . $stmt_update->error;
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
