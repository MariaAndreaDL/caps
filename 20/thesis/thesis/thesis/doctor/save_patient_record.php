<?php
// Include the database connection
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $patient_id = $_POST['patient_id'];
    $consultation_date = $_POST['consultation_date'];
    $blood_pressure = $_POST['blood_pressure'];
    $weight = $_POST['weight'];
    $diagnoses = $_POST['diagnosis'];
    $services = $_POST['service_id'];
    $remarks = $_POST['remarks'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO patient_records (patient_id, consultation_date, diagnosis, service_id, blood_pressure, weight, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    // Bind and execute each record entry
    foreach ($diagnoses as $index => $diagnosis) {
        $service_id = $services[$index];
        $remark = $remarks[$index];

        // Bind variables to the prepared statement
        $stmt->bind_param('isssdds', $patient_id, $consultation_date, $diagnosis, $service_id, $blood_pressure, $weight, $remark);

        // Execute the statement
        if (!$stmt->execute()) {
            die("Execution failed: " . $stmt->error);
        }
    }

    // Close the connection and redirect
    $stmt->close();
    $conn->close();

    header("Location: view_patient_record.php?id=" . urlencode($patient_id));
    exit();
} else {
    header("Location: patient_record_entry.php");
    exit();
}
?>
