<?php
require_once('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $loan_name = $_POST['loan_name'];
    $loan_duration = $_POST['loan_duration'];
    $maximum_limit = $_POST['maximum_limit'];
    $loan_guarantors = $_POST['loan_guarantors'];
    $loan_description = $_POST['loan_description'];

    // Check if the product exists
    $check_product_sql = "SELECT product_id FROM products WHERE product_id = ?";
    if ($stmt_product = $mysqli->prepare($check_product_sql)) {
        $stmt_product->bind_param('i', $product_id);
        $stmt_product->execute();
        $stmt_product->store_result();

        if ($stmt_product->num_rows > 0) {
            $stmt_product->close(); 

            // Prepare the SQL statement to update the product
            $sql = "UPDATE products SET 
                        loan_name = ?, 
                        loan_duration = ?, 
                        maximum_limit = ?, 
                        loan_guarantors = ?, 
                        loan_description = ?
                    WHERE product_id = ?";

            if ($stmt_update = $mysqli->prepare($sql)) {
                $stmt_update->bind_param(
                    'siissi',
                    $loan_name, $loan_duration,
                    $maximum_limit, $loan_guarantors,
                    $loan_description, $product_id
                );

                if ($stmt_update->execute()) {
                    echo "<script>alert('Product updated successfully.');</script>";
                    $_SESSION['success'] = 'Product updated successfully.';
                    header('Location: ../views/products');
                    exit();
                } else {
                    echo "Error updating product: " . $stmt_update->error;
                }

                $stmt_update->close();
            } else {
                echo "Error preparing update query: " . $mysqli->error;
            }
        } else {
            echo "Error: Product not found.";
        }

        $stmt_product->close();
    } else {
        echo "Error checking product: " . $mysqli->error;
    }
}
?>
