<?php
// add_category.php

// Include database connection
include 'config.php';
include 'sidebar.php';
include 'styles.css';

// Initialize variables
$errors = [];
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);

    // Validate input
    if (empty($category_name)) {
        $errors[] = "Category name cannot be empty.";
    } else {
        // Insert new category into the database
        $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            $success_message = "Category added successfully!";
        } else {
            $errors[] = "Error adding category: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar Placeholder -->
    <div class="sidebar-placeholder"></div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1>Add New Category</h1>

            <!-- Success Message -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Add Category Form -->
            <form method="post" action="">
                <input type="text" name="category_name" placeholder="Enter Category Name" required>
                <button type="submit">Add Category</button>
            </form>
        </div>
    </div>
</body>
</html>
