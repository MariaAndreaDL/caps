<?php
// Assume this is the top of your PHP file where you handle sessions or any required setups
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure this path is correct -->
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Drolah</h2>
        </div>
        <ul>
            <li class="active"><a href="#">Dashboard</a></li>
            <li>
            
        <li>
            <a href="#">Client</a>
            <ul>
                <li>
                    <a href="#">Patient</a>
                    <ul>
                        <li><a href="patient_list.php">Patient List</a></li>
                        <li><a href="add_patient.php">Add Patient</a></li>
                        <li><a href="add_record.php">Add Record</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Prescription</a>
                    <ul>
                        <li><a href="pres_history.php">Prescription History</a></li>
                        <li><a href="med_cert.php">Medical Certificate</a></li>
                        <li><a href="med_pres.php">Medical Prescription</a></li>
                        <li><a href="cos_pres.php">Cosmetics Prescription</a></li>
                    </ul>
                </li>
            </ul>
        </li>

    </ul>
</div>

    <script>
        // JavaScript to handle active states
        document.querySelectorAll('.sidebar ul li a').forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.sidebar ul li').forEach(li => li.classList.remove('active', 'sub-active'));
                
                // Add active class to the clicked item
                this.parentElement.classList.add('active');

                // Add active class for subcategories
                if (this.nextElementSibling) {
                    this.nextElementSibling.querySelectorAll('a').forEach(sub => {
                        sub.classList.remove('sub-active'); // Remove any existing active class
                    });
                    this.classList.add('sub-active'); // Add active class to the clicked subcategory
                }
            });
        });
    </script>

</body>
</html>


