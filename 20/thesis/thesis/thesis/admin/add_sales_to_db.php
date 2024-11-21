<?php
// add_sales_to_db.php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $subtotals = $_POST['subtotal'];
    $discount_percent = isset($_POST['discount_percent']) ? floatval($_POST['discount_percent']) : 0;
    $sale_date = date('Y-m-d H:i:s'); // Current date and time

    // Debugging: Log received data
    file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);

    // Prepare the SQL statement for inserting sales details
    $sql = "INSERT INTO sales_details (name, product_id, quantity, subtotal, discount_percent, sales_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Loop through each product to insert multiple rows for each sale
    for ($i = 0; $i < count($product_ids); $i++) {
        $product_id = $product_ids[$i];
        $quantity = $quantities[$i];
        $subtotal = $subtotals[$i];

        // Apply discount to subtotal
        if ($discount_percent > 0) {
            $discount_amount = ($subtotal * $discount_percent) / 100;
            $subtotal -= $discount_amount;
        }

        // Check if product ID, quantity, and subtotal are not empty
        if (!empty($product_id) && !empty($quantity) && !empty($subtotal)) {
            // Bind parameters and execute for each row
            $stmt->bind_param("siidss", $name, $product_id, $quantity, $subtotal, $discount_percent, $sale_date);

            if ($stmt->execute()) {
                // Update stock in products table
                $update_stock_sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
                $update_stock_stmt = $conn->prepare($update_stock_sql);

                if ($update_stock_stmt) {
                    $update_stock_stmt->bind_param("ii", $quantity, $product_id);
                    $update_stock_stmt->execute();
                    $update_stock_stmt->close();
                } else {
                    echo "Error updating stock: " . $conn->error;
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    $stmt->close();
    $conn->close();

    // Redirect to sales_history.php
    header("Location: sales_history.php");
    exit;
} else {
    echo "Invalid request method.";
}
?>
