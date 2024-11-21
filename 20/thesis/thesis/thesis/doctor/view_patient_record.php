<?php
// view_patient_record.php

include 'config.php';

// Function to fetch patient data
function fetchPatientData($conn, $patient_id) {
    $patient_query = "SELECT * FROM patients WHERE patient_id = ?";
    $stmt = $conn->prepare($patient_query);
    
    if (!$stmt) {
        die('Query preparation failed: ' . $conn->error);
    }

    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to fetch patient records with service names
function fetchPatientRecords($conn, $patient_id) {
    $records_query = "
        SELECT pr.consultation_date, pr.diagnosis, pr.blood_pressure, pr.weight, pr.remarks, s.service_name
        FROM patient_records pr
        JOIN services s ON pr.service_id = s.service_id
        WHERE pr.patient_id = ?";
    $stmt_records = $conn->prepare($records_query);
    
    if (!$stmt_records) {
        die('Query preparation failed: ' . $conn->error);
    }

    $stmt_records->bind_param('i', $patient_id);
    $stmt_records->execute();
    return $stmt_records->get_result();
}

// Get the patient ID from the URL
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Fetch patient details
    $patient = fetchPatientData($conn, $patient_id);

    // Fetch patient records
    $records_result = fetchPatientRecords($conn, $patient_id);
} else {
    // Redirect if no ID is provided
    header("Location: patient_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            margin-top: 40px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
            font-weight: 500;
        }
        .patient-info, .patient-records {
            margin-bottom: 20px;
        }
        .patient-info table {
            width: 100%;
        }
        .patient-info td {
            padding: 8px;
            border-bottom: 1px solid #e9ecef;
            color: #555;
        }
        .patient-info td.label {
            font-weight: 500;
            width: 30%;
            color: #333;
        }
        .table {
            margin-top: 20px;
            font-size: 0.9em;
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        .table th {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
        }
        .table td {
            background-color: #f8f9fa;
            border: none;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Patient Record</h2>

        <?php if ($patient): ?>
            <div class="patient-info">
                <h4>Patient Information</h4>
                <table>
                    <tr>
                        <td class="label">Full Name:</td>
                        <td><?php echo htmlspecialchars($patient['full_name']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Address:</td>
                        <td><?php echo htmlspecialchars($patient['address']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Age:</td>
                        <td><?php echo htmlspecialchars($patient['age']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Gender:</td>
                        <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Contact Number:</td>
                        <td><?php echo htmlspecialchars($patient['contact_number']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Marital Status:</td>
                        <td><?php echo htmlspecialchars($patient['marital_status']); ?></td>
                    </tr>
                </table>
            </div>

            <div class="patient-records">
                <h4>Patient Records</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Consultation Date</th>
                            <th>Diagnosis</th>
                            <th>Service Name</th>
                            <th>Blood Pressure</th>
                            <th>Weight</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($records_result->num_rows > 0): ?>
                            <?php while ($record = $records_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($record['consultation_date']); ?></td>
                                    <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                                    <td><?php echo htmlspecialchars($record['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($record['blood_pressure']); ?></td>
                                    <td><?php echo htmlspecialchars($record['weight']); ?></td>
                                    <td><?php echo htmlspecialchars($record['remarks']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Add New Record Button -->
                <div class="text-right">
                    <a href="add_record.php?id=<?php echo $patient_id; ?>" class="btn btn-primary">Add New Record</a>
                </div>
            </div>
        <?php else: ?>
            <p>Patient not found.</p>
        <?php endif; ?>
    </div>

    <?php
    // Close the connection
    $conn->close();
    ?>
</body>
</html>
