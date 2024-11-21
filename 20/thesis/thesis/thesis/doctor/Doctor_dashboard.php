<?php
// Add any required session or PHP setup logic here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* General Body Styles */
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            background-color: #fef6f6; /* Light Beige */
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #f3eae3; /* Soft Cream */
            color: #4d4d4d; /* Neutral Dark Gray */
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            color: white;
            background-color: #d3cbc6; /* Light Taupe */
            border-top-right-radius: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #4d4d4d;
            padding: 12px 15px;
            border-radius: 12px;
            margin: 0 15px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a .icon {
            font-size: 18px;
            margin-right: 10px;
        }

        .sidebar ul li a:hover,
        .sidebar ul li.active > a {
            background-color: #e6d6cf; /* Neutral Beige */
            color: #4d4d4d;
            font-weight: bold;
        }

        .sidebar ul li ul {
            list-style: none;
            margin: 0;
            padding: 5px 15px;
            display: none; /* Initially hide submenus */
        }

        .sidebar ul li ul li a {
            padding-left: 35px;
            font-size: 14px;
        }

        .sidebar ul li ul.submenu {
            margin-top: 5px;
        }

        .sidebar ul li ul.submenu.show {
            display: block; /* Show submenu when toggled */
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        h1 {
            font-size: 28px;
            color: #4d4d4d;
        }

        p {
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome to Drolah Dashboard</h1>
        <p>This is the main dashboard where you can navigate through various sections like Clients, Patients, and Prescriptions.</p>
    </div>

    <script>
        // Toggle submenu visibility
        document.querySelectorAll('.submenu-toggle').forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default link behavior
                const submenu = this.nextElementSibling;

                // Toggle visibility of the clicked submenu
                submenu.classList.toggle('show');

                // Close other submenus
                document.querySelectorAll('.submenu').forEach(otherSubmenu => {
                    if (otherSubmenu !== submenu) {
                        otherSubmenu.classList.remove('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
