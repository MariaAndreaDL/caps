<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fdfdfd;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-container {
            display: flex;
            justify-content: flex-end; /* Align the button to the right */
            margin-bottom: 20px;
        }

        .btn {
            background-color: #f27d9d;
            border: none;
            color: white;
            text-align: center;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 10px; /* Add spacing below the button */
        }

        .btn:hover {
            background-color: #d66b88;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px; /* Increased margin to separate from the button */
        }

        .card-header {
            background-color: #f27d9d;
            color: white;
            padding: 10px 15px;
            font-size: 18px;
        }

        .card-body {
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .delete-icon {
            color: #d33;
            cursor: pointer;
        }

        .delete-icon:hover {
            color: #a00;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>

    <!-- Sidebar Placeholder -->
    <div class="sidebar-placeholder"></div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2>Manage Categories</h2>

            <!-- Add New Category Button -->
            <div class="btn-container">
                <a href="add_category.php" class="btn">Add New Category</a>
            </div>

            <!-- Categories List -->
            <div class="card">
                <div class="card-header">All Categories</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_categories as $cat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cat['category_id']); ?></td>
                                    <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                                    <td>
                                        <i class="fas fa-trash-alt delete-icon" onclick="confirmDelete(<?php echo $cat['category_id']; ?>)"></i>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="footer">&copy; 2024 Inventory System</footer>
    </div>

    <script>
        function confirmDelete(categoryId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This category will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_category.php?id=' + categoryId;
                }
            });
        }
    </script>

</body>
</html>
