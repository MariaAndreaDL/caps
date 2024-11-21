<?php
// Include database connection
include 'config.php';
include 'styles.css';

// Initialize variables
$first_name = $last_name = $middle_initial = $address = $age = $gender = $contact_number = $marital_status = $remarks = "";
$errors = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_initial = $_POST['middle_initial'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];
    $marital_status = $_POST['marital_status'];
    $remarks = $_POST['remarks'];

    // Combine name fields
    $full_name = trim("$last_name, $first_name $middle_initial");

    // Check if all required fields are filled
    if (empty($first_name) || empty($last_name) || empty($address) || empty($age) || empty($gender) || empty($contact_number) || empty($marital_status)) {
        $errors = "All fields are required.";
    } else {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO patients (full_name, address, age, gender, contact_number, marital_status, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        // Check if statement preparation was successful
        if ($stmt) {
            $stmt->bind_param("ssissss", $full_name, $address, $age, $gender, $contact_number, $marital_status, $remarks);

            // Execute the statement
            if ($stmt->execute()) {
                // Get the last inserted patient ID
                $patient_id = $stmt->insert_id;

                // Patient added successfully, set a success message with SweetAlert and redirect
                echo "<script>
                        swal({
                            title: 'Success!',
                            text: 'Patient added successfully!',
                            icon: 'success',
                            button: 'OK',
                        }).then(function() {
                            window.location.href = 'view_patient_record.php?id=$patient_id';
                        });
                      </script>";
                exit;
            } else {
                $errors = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $errors = "Error preparing statement: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1>Add New Patient</h1>

        <!-- Display errors if any -->
        <?php if (!empty($errors)): ?>
            <div class="error"><?php echo $errors; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" required>
            <input type="text" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            <input type="text" name="middle_initial" placeholder="Middle Initial" value="<?php echo htmlspecialchars($middle_initial); ?>" maxlength="1">

            <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($address); ?>" required>
            <input type="number" name="age" placeholder="Age" value="<?php echo htmlspecialchars($age); ?>" required>
            
            <select name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
            </select>

            <input type="text" name="contact_number" placeholder="Contact Number" value="<?php echo htmlspecialchars($contact_number); ?>" required>
            
            <select name="marital_status" required>
                <option value="" disabled selected>Select Marital Status</option>
                <option value="Single" <?php if ($marital_status == 'Single') echo 'selected'; ?>>Single</option>
                <option value="Married" <?php if ($marital_status == 'Married') echo 'selected'; ?>>Married</option>
                <option value="Divorced" <?php if ($marital_status == 'Divorced') echo 'selected'; ?>>Divorced</option>
            </select>

            <input type="text" name="remarks" placeholder="Remarks (if any)" value="<?php echo htmlspecialchars($remarks); ?>">

            <button type="submit">Add Patient</button>
    </div>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.toggle-btn');

        // Toggle sidebar collapse
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
