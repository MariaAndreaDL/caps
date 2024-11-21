<?php
// Include your database configuration
include 'config.php';

// Fetch patients
$patient_query = "SELECT patient_id, full_name, age, gender, marital_status, address FROM patients";
$patient_result = $conn->query($patient_query);

// Store patient data in an array for the dropdown menu and auto-fill feature
$patients = [];
while ($row = $patient_result->fetch_assoc()) {
    $patients[] = $row;
}

// Handle saving the certificate
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_certificate'])) {
    // Get the data from the form
    $patient_id = $_POST['patient_id'];
    $medical_conditions = $_POST['medical_conditions'];
    $remarks = $_POST['remarks'];
    $issued_date = date('Y-m-d'); // Current date

    // Save certificate to the database
    $save_query = "INSERT INTO medical_certificates (patient_id, medical_conditions, remarks, issued_date) 
                   VALUES ('$patient_id', '$medical_conditions', '$remarks', '$issued_date')";

    if ($conn->query($save_query) === TRUE) {
        $message = "Certificate saved successfully!";
        echo "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire('Success!', '$message', 'success'); });</script>";
    } else {
        $message = "Error saving certificate: " . $conn->error;
        echo "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire('Error!', '$message', 'error'); });</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <style>
        @media print {
            button, select {
                display: none;
            }
            .certificate-container {
                margin: 0;
                padding: 0;
                border: none;
            }
        }
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f8ff;
        }
        .certificate-container {
            width: 70%;
            padding: 30px;
            border: 2px solid #333;
            border-radius: 15px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 20px;
        }
        .header, .footer {
            text-align: center;
            font-weight: bold;
        }
        .header img {
            width: 120px;
            height: 120px;
            margin-bottom: 10px;
            border: 2px solid #ddd;
            border-radius: 50%;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            border: none;
            border-bottom: 1px solid #333;
            background-color: #f8f9fa;
            margin-bottom: 10px;
        }
        .date {
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="certificate-container">
    <div class="header">
        <img src="path_to_placeholder_image.png" alt="Patient's Picture">
        <h2>Medical Certificate</h2>
    </div>
    
    <form id="certForm" method="POST">
        <div class="form-group">
            <label>To Whom It May Concern,</label>
            <div class="date">Date: <?php echo date('Y-m-d'); ?></div>
        </div>

        <p>
            This is to certify that 
            <select id="patientSelect" class="form-control font-weight-bold" name="patient_id" style="display: inline; width: auto;">
                <option value="">Select Patient</option>
                <?php foreach ($patients as $patient) : ?>
                    <option value="<?= $patient['patient_id'] ?>">
                        <?= htmlspecialchars($patient['full_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>, 
            Age <span id="patientAgeLine" class="font-weight-bold">____</span>, 
            Sex <span id="patientSexLine" class="font-weight-bold">____</span>, 
            Civil Status <span id="patientStatusLine" class="font-weight-bold">________________</span>, 
            residing at <span id="patientAddressLine" class="font-weight-bold">____________________________</span>, 
            has been under my medical care for the following medical condition(s):
        </p>
        
        <div class="form-group">
            <textarea class="form-control font-weight-bold" rows="3" name="medical_conditions" placeholder="Enter medical condition(s) here"></textarea>
        </div>

        <p>This certification is being issued upon request to be used exclusively for medical purposes.</p>

        <div class="form-group">
            <label>Remarks:</label>
            <textarea class="form-control font-weight-bold" rows="3" name="remarks" placeholder="Enter remarks here"></textarea>
        </div>

        <div class="footer">
            Doctor 1234342 <br>
            Lic No. 123124 <br>
            PTR No.
        </div>

        <!-- Buttons for Save and Print -->
        <button type="submit" name="save_certificate" class="btn btn-primary">Save Certificate</button>
        <button type="button" onclick="window.print()" class="btn btn-secondary">Print Certificate</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('#patientSelect').on('change', function() {
            const patientId = $(this).val();
            const patientData = <?= json_encode($patients); ?>;

            if (patientId) {
                const patient = patientData.find(p => p.patient_id == patientId);
                if (patient) {
                    $('#patientAgeLine').text(patient.age);
                    $('#patientSexLine').text(patient.gender);
                    $('#patientStatusLine').text(patient.marital_status);
                    $('#patientAddressLine').text(patient.address);
                }
            } else {
                $('#patientAgeLine').text('____');
                $('#patientSexLine').text('____');
                $('#patientStatusLine').text('________________');
                $('#patientAddressLine').text('____________________________');
            }
        });
    });
</script>
</body>
</html>
