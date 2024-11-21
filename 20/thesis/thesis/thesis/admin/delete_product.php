<?php
// delete_product.php

include 'config.php'; // Database connection

// Check if the product ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare the delete statement to prevent SQL injection
    $delete_query = $conn->prepare("DELETE FROM products WHERE id = ?");
    $delete_query->bind_param("i", $product_id);

    if ($delete_query->execute()) {
        echo "Product deleted successfully.";
        header("Location: product_list.php");
        exit;
    } else {
        echo "Error deleting product: " . $delete_query->error;
    }

    // Close the prepared statement
    $delete_query->close();
} else {
    echo "No product ID provided or invalid ID.";
}

// Close the database connection
$conn->close();
?>
