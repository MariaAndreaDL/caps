<?php
// Include database connection
include 'config.php';

// Initialize error message
$error_message = '';

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $category_id = (int)$_GET['id'];

    // Prepare and execute the deletion query
    $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        // Redirect back to categories page with success message
        header("Location: categories.php?message=Category+deleted+successfully!");
        exit;
    } else {
        $error_message = "Error deleting category: " . $stmt->error;
    }
    $stmt->close();
} else {
    $error_message = "No category ID provided for deletion.";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Category</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="container">
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <a href="categories.php" class="btn btn-primary">Back to Categories</a>
    </div>
</body>
</html>
