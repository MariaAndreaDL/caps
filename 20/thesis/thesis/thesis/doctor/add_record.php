<?php
// Include the database connection
include 'config.php';

// Get the patient ID from the URL if provided
$patient_id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch specific patient data if patient ID is provided
$selected_patient = null;
if ($patient_id) {
    $stmt = $conn->prepare("SELECT patient_id, full_name FROM patients WHERE patient_id = ?");
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $selected_patient = $stmt->get_result()->fetch_assoc();
}

// Fetch services for the dropdown
$services_result = $conn->query("SELECT service_id, service_name FROM services");
$services = $services_result->fetch_all(MYSQLI_ASSOC);

// Handle form submission within the same file
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $patient_id = $_POST['patient_id'] ?? null;
    $consultation_date = $_POST['consultation_date'] ?? null;
    $blood_pressure = $_POST['blood_pressure'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $diagnoses = $_POST['diagnosis'] ?? [];
    $services = $_POST['service_id'] ?? [];
    $remarks = $_POST['remarks'] ?? [];

    // Validate blood pressure format (optional, can be removed if not needed)
    if (!preg_match('/^\d{2,3}\/\d{2,3}$/', $blood_pressure)) {
        die("Invalid blood pressure format. Use format like 120/80.");
    }

    // Check if essential data exists
    if ($patient_id && $consultation_date && !empty($diagnoses) && !empty($services)) {
        // Prepare SQL statement with 's' for blood pressure as a string
        $stmt = $conn->prepare("INSERT INTO patient_records (patient_id, consultation_date, diagnosis, service_id, blood_pressure, weight, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            foreach ($diagnoses as $index => $diagnosis) {
                $service_id = $services[$index];
                $remark = $remarks[$index] ?? '';

                // Bind variables with 's' for blood pressure as a string
                $stmt->bind_param('isssdds', $patient_id, $consultation_date, $diagnosis, $service_id, $blood_pressure, $weight, $remark);

                // Execute the statement
                if (!$stmt->execute()) {
                    die("Execution failed: " . $stmt->error);
                }
            }

            // Close statement and redirect
            $stmt->close();
            $conn->close();
            header("Location: view_patient_record.php?id=" . urlencode($patient_id));
            exit();
        } else {
            die("Preparation failed: " . $conn->error);
        }
    } else {
        echo "<script>alert('Please fill out all required fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Record Entry</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .patient-section { display: flex; gap: 10px; }
        .patient-name { flex: 3; }
        .consultation-date { flex: 1; }
        .vitals-section { display: flex; gap: 10px; }
        .blood-pressure, .weight { flex: 1; }
        .auto-expand { min-height: 38px; overflow-y: hidden; resize: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Patient Record Entry</h2>
        <form method="POST" id="patientRecordForm">
            <!-- Patient and Consultation Date Section -->
            <div class="patient-section">
                <div class="form-group patient-name">
                    <label for="patient_id">Patient:</label>
                    <?php if ($selected_patient): ?>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($selected_patient['full_name']) ?>" readonly>
                        <input type="hidden" name="patient_id" value="<?= htmlspecialchars($selected_patient['patient_id']) ?>">
                    <?php else: ?>
                        <select name="patient_id" id="patient_id" class="form-control" required>
                            <option value="">Select Patient</option>
                            <?php
                            $patients_result = $conn->query("SELECT patient_id, full_name FROM patients");
                            $patients = $patients_result->fetch_all(MYSQLI_ASSOC);
                            foreach ($patients as $patient):
                            ?>
                                <option value="<?= htmlspecialchars($patient['patient_id']) ?>"><?= htmlspecialchars($patient['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="form-group consultation-date">
                    <label for="consultation_date">Consultation Date:</label>
                    <input type="datetime-local" name="consultation_date" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" readonly>
                </div>
            </div>

            <!-- Blood Pressure and Weight Section -->
            <div class="vitals-section">
                <div class="form-group blood-pressure">
                    <label for="blood_pressure">Blood Pressure:</label>
                    <input type="text" name="blood_pressure" class="form-control" placeholder="Enter BP (e.g., 120/80)" required>
                </div>
                <div class="form-group weight">
                    <label for="weight">Weight:</label>
                    <input type="number" name="weight" class="form-control" placeholder="Enter weight" required>
                </div>
            </div>

            <!-- Record Entry Section -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Diagnosis</th>
                        <th>Service</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="record-entries">
                    <tr>
                        <td><textarea name="diagnosis[]" class="form-control auto-expand" required></textarea></td>
                        <td>
                            <select name="service_id[]" class="form-control" required>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?= htmlspecialchars($service['service_id']) ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><textarea name="remarks[]" class="form-control auto-expand"></textarea></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" onclick="addRow()">Add More Entry</button>
            <button type="submit" class="btn btn-success">Save Records</button>
        </form>
    </div>

    <script>
        // Add row function
        function addRow() {
            const tableBody = document.getElementById('record-entries');
            const newRow = `
                <tr>
                    <td><textarea name="diagnosis[]" class="form-control auto-expand" required></textarea></td>
                    <td>
                        <select name="service_id[]" class="form-control" required>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= htmlspecialchars($service['service_id']) ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><textarea name="remarks[]" class="form-control auto-expand"></textarea></td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);
        }

        // SweetAlert after form submission
        document.getElementById("patientRecordForm").onsubmit = function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Success!',
                text: 'Patient record saved successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                document.getElementById("patientRecordForm").submit();
            });
        };
    </script>
</body>
</html>
