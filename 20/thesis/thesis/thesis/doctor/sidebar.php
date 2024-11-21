<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Collapsible Menus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f5f2; /* Warm Beige */
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #f3eae3; /* Soft Cream */
            color: #4d4d4d; /* Neutral Dark Gray */
            height: 100vh;
            position: fixed;
            overflow-y: auto;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-size: 20px;
            font-weight: bold;
            background-color: #d3cbc6; /* Light Taupe */
            color: #ffffff;
            border-top-right-radius: 30px;
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
            margin: 0 15px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a .icon {
            font-size: 18px;
            margin-right: 12px;
            color: #a89b93; /* Soft Grayish Brown */
        }

        .sidebar ul li a .text {
            font-size: 16px;
            white-space: nowrap;
        }

        .sidebar ul li a:hover,
        .sidebar ul li.active > a {
            background-color: #e6d6cf; /* Light Neutral Beige */
            color: #4d4d4d;
            font-weight: bold;
        }

        /* Nested Submenu Styles */
        .sidebar ul li ul {
            display: none;
            margin-left: 15px;
        }

        .sidebar ul li ul.open {
            display: block;
        }

        .sidebar ul li ul li a {
            font-size: 14px;
            padding: 8px 20px;
            background-color: #f3ebe7; /* Very Light Taupe */
            border-radius: 8px;
        }

        .sidebar ul li ul li a:hover {
            background-color: #d8ccc5; /* Soft Beige */
        }

        /* Logout Button */
        .logout {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
        }

        .logout a {
            display: inline-block;
            text-decoration: none;
            color: #ffffff;
            background-color: #d9534f; /* Bootstrap Danger Red */
            padding: 10px 20px;
            border-radius: 15px;
            transition: background-color 0.3s;
            font-size: 16px;
            font-weight: bold;
        }

        .logout a:hover {
            background-color: #c9302c; /* Slightly Darker Red */
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">Drolah</div>
    <ul>
        <!-- Dashboard -->
        <li class="active"><a href="#"><i class="fas fa-tachometer-alt icon"></i> Dashboard</a></li>

        <!-- Client -->
        <li>
            <a href="#" class="submenu-toggle"><i class="fas fa-users icon"></i> Client</a>
            <ul class="submenu">
                <!-- Patient Submenu -->
                <li>
                    <a href="#" class="submenu-toggle"><i class="fas fa-user icon"></i> Patient</a>
                    <ul class="submenu">
                        <li><a href="patient_list.php"><i class="fas fa-list icon"></i> Patient List</a></li>
                        <li><a href="add_patient.php"><i class="fas fa-user-plus icon"></i> Add Patient</a></li>
                        <li><a href="add_record.php"><i class="fas fa-file-medical icon"></i> Add Record</a></li>
                    </ul>
                </li>

                <!-- Prescription Submenu -->
                <li>
                    <a href="#" class="submenu-toggle"><i class="fas fa-prescription icon"></i> Prescription</a>
                    <ul class="submenu">
                        <li><a href="pres_history.php"><i class="fas fa-history icon"></i> Prescription History</a></li>
                        <li><a href="med_cert.php"><i class="fas fa-certificate icon"></i> Medical Certificate</a></li>
                        <li><a href="med_pres.php"><i class="fas fa-file-prescription icon"></i> Medical Prescription</a></li>
                        <li><a href="cos_pres.php"><i class="fas fa-prescription-bottle-alt icon"></i> Cosmetic Prescription</a></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>

<script>
    // Toggle Submenus
    document.querySelectorAll('.submenu-toggle').forEach(link => {
        link.addEventListener('click', (e) => {
            const submenu = link.nextElementSibling;

            if (submenu && submenu.tagName === 'UL') {
                // Prevent default for links with submenus
                e.preventDefault();
                submenu.classList.toggle('open');
            }
        });
    });
</script>

</body>
</html>
