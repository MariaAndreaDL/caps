<?php
// delete_service_category.php

// Include database connection
include 'config.php';

// Check if the service category ID is provided
if (isset($_GET['id'])) {
    $service_category_id = $_GET['id'];

    // Delete the service category from the database
    $delete_query = "DELETE FROM services_category WHERE service_category_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $service_category_id);

    if ($stmt->execute()) {
        // Redirect back to services_category.php with a success message
        header("Location: services_category.php?message=Service category deleted successfully");
        exit;
    } else {
        echo "Error deleting service category: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No service category ID provided.";
}

// Close the database connection
$conn->close();
?>
