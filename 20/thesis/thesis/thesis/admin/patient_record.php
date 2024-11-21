<?php
// patient_record.php

include 'config.php';

// Check if the patient ID is provided
if (isset($_GET['id'])) {
    $patient_id = (int)$_GET['id']; // Cast to int for safety

    // Query to fetch the patient record based on the provided ID
    $query = "SELECT * FROM patients WHERE id = $patient_id";
    $result = $conn->query($query);

    // Check if the patient record exists
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        // Patient not found
        echo "No patient found with this ID.";
        exit;
    }
} else {
    // No ID provided
    echo "No patient ID specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Record</title>
    <style>
        /* styles.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .patient-info {
            margin-bottom: 20px;
        }

        .patient-info label {
            font-weight: bold;
        }

        .back-button {
            display: block;
            margin: 20px auto;
            padding: 10px 15px;
            text-align: center;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Patient Record</h1>

        <div class="patient-info">
            <label>Full Name:</label> <span><?php echo htmlspecialchars($patient['full_name']); ?></span><br>
            <label>Address:</label> <span><?php echo htmlspecialchars($patient['address']); ?></span><br>
            <label>Age:</label> <span><?php echo htmlspecialchars($patient['age']); ?></span><br>
            <label>Gender:</label> <span><?php echo htmlspecialchars($patient['gender']); ?></span><br>
            <label>Contact Number:</label> <span><?php echo htmlspecialchars($patient['contact_number']); ?></span><br>
            <label>Marital Status:</label> <span><?php echo htmlspecialchars($patient['marital_status']); ?></span><br>
            <label>Date of Check-up:</label> <span><?php echo htmlspecialchars($patient['date_of_checkup']); ?></span><br>
            <label>Diagnosis:</label> <span><?php echo htmlspecialchars($patient['diagnosis']); ?></span><br>
            <label>Treatment/Remarks:</label> <span><?php echo htmlspecialchars($patient['treatment_remarks']); ?></span><br>
            <label>Blood Pressure:</label> <span><?php echo htmlspecialchars($patient['blood_pressure']); ?></span><br>
            <label>Weight:</label> <span><?php echo htmlspecialchars($patient['weight']); ?></span><br>
        </div>

        <a class="back-button" href="patient_list.php">Back to Patient List</a>
    </div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
