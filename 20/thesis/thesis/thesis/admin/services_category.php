<?php
// services_category.php

// Include database connection
include 'config.php';
include 'sidebar.php';

// Initialize variables
$errors = [];
$success_message = '';
$all_services_categories = [];

// Fetch all services categories from the database
$result = $conn->query("SELECT * FROM services_category");
if ($result) {
    $all_services_categories = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $errors[] = "Error fetching services categories.";
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services Categories</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        <?php echo file_get_contents("styles.css"); ?> /* Embed your CSS */
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <span>My Sidebar</span>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home icon"></i> Dashboard</a></li>
            <li><a href="services_category.php" class="active"><i class="fas fa-tags icon"></i> Services Category</a></li>
            <li><a href="service_list.php"><i class="fas fa-list icon"></i> Service List</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt icon"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Manage Services Categories</h2>
        <div class="container">
            <div class="row">
                <!-- Button to Add Services Category -->
                <div class="col-md-12 mb-3">
                    <a href="add_category.php" class="btn btn-pink">
                        <i class="fas fa-plus-circle"></i> Add Service Category
                    </a>
                </div>

                <!-- List of Services Categories -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            All Service Categories
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Service Category Name</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_services_categories as $cat): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(ucfirst($cat['service_category_name'])); ?></td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);" 
                                                   onclick="confirmDelete(<?php echo (int)$cat['service_category_id']; ?>)">
                                                    <i class="fas fa-trash-alt text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this category!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location.href = "delete_service_category.php?id=" + id;
                }
            });
        }
    </script>
</body>
</html>
