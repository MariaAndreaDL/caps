<?php
// Include the database connection
include 'config.php';

if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']); // Get the service ID and ensure it's an integer

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM services WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        // Redirect back to the services list page
        header("Location: services_list.php?message=Service deleted successfully");
        exit();
    } else {
        echo "Error deleting service: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No service ID provided.";
}

// Close the connection
$conn->close();
?>
