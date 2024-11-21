<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>Dashboard</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>

                <!-- Inventory Section -->
                <li class="dropdown">
                    <a href="#">Inventory</a>
                    <ul class="dropdown-content">
                        <li><a href="categories.php">Categories</a></li>
                        <li class="dropdown">
                            <a href="#">Products</a>
                            <ul class="dropdown-content">
                                <li><a href="manage_products.php">Manage Products</a></li>
                                <li><a href="add_product.php">Add Product</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Sales Section -->
                <li class="dropdown">
                    <a href="#">Sales</a>
                    <ul class="dropdown-content">
                        <li><a href="manage_sales.php">Manage Sales</a></li>
                        <li><a href="add_sale.php">Add Sale</a></li>
                        <li class="dropdown">
                            <a href="#">Sales Report</a>
                            <ul class="dropdown-content">
                                <li><a href="sales_by_date.php">Sales by Date</a></li>
                                <li><a href="monthly_sales.php">Monthly Sales</a></li>
                                <li><a href="daily_sales.php">Daily Sales</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Client Record Section -->
                <li><a href="client_record.php">Client Record</a></li>

                <!-- User Management -->
                <li><a href="user_management.php">User Management</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Welcome to the Dashboard!</h2>
            <p>Select an option from the sidebar to manage the system.</p>
        </div>
    </div>

    <!-- jQuery to handle the dropdown functionality -->
    <script>
        $(document).ready(function(){
            // Toggle dropdown for Inventory
            $('.dropdown > a').click(function(){
                $(this).next('.dropdown-content').slideToggle();
            });
        });
    </script>
</body>
</html>
