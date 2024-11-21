<?php
// Include database connection
include 'config.php';

// Initialize variables
$patient_id = $_GET['id'] ?? '';
$first_name = $last_name = $middle_initial = '';
$address = $age = $gender = $contact_number = $marital_status = '';

// Fetch patient data for editing
if ($patient_id) {
    $stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Split full_name into parts (assumes format "Last Name, First Name M.")
        $name_parts = explode(',', $row['full_name']);
        $last_name = trim($name_parts[0] ?? '');
        $first_name_middle = trim($name_parts[1] ?? '');

        // Separate first name and middle initial
        $first_name_parts = explode(' ', $first_name_middle);
        $first_name = trim($first_name_parts[0] ?? '');
        $middle_initial = isset($first_name_parts[1]) ? $first_name_parts[1][0] : ''; // Middle initial, if exists

        $address = $row['address'];
        $age = $row['age'];
        $gender = $row['gender'];
        $contact_number = $row['contact_number'];
        $marital_status = $row['marital_status'];
    }
}

// Handle form submission for updating patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input values
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_initial = $_POST['middle_initial'];
    $address = $_POST['address'];
    $age = (int) $_POST['age'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];
    $marital_status = $_POST['marital_status'];

    // Combine name fields
    $full_name = trim("$last_name, $first_name $middle_initial");

    // Update patient record
    $update_stmt = $conn->prepare("UPDATE patients SET full_name = ?, address = ?, age = ?, gender = ?, contact_number = ?, marital_status = ? WHERE patient_id = ?");
    $update_stmt->bind_param("ssisssi", $full_name, $address, $age, $gender, $contact_number, $marital_status, $patient_id);

    if ($update_stmt->execute()) {
        // Redirect to patient_list.php with success message
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Patient updated successfully!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'patient_list.php';
                    }
                });
              </script>";
    } else {
        // Display error message if update fails
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error updating patient.',
                    text: 'Please try again.',
                    confirmButtonText: 'OK'
                });
              </script>";
    }

    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(to right, #d16ba5, #f89456);
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Patient</h1>
        <form method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="middle_initial">Middle Initial</label>
                <input type="text" class="form-control" name="middle_initial" value="<?php echo htmlspecialchars($middle_initial); ?>" maxlength="1">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($age); ?>" required min="0">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender" required>
                    <option value="Male" <?php if ($gender === 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($gender === 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($gender === 'Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" required>
            </div>
            <div class="form-group">
                <label for="marital_status">Marital Status</label>
                <select class="form-control" name="marital_status" required>
                    <option value="Single" <?php if ($marital_status === 'Single') echo 'selected'; ?>>Single</option>
                    <option value="Married" <?php if ($marital_status === 'Married') echo 'selected'; ?>>Married</option>
                    <option value="Divorced" <?php if ($marital_status === 'Divorced') echo 'selected'; ?>>Divorced</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Patient</button>
            <a href="patient_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</body>
</html>
