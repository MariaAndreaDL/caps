<?php
// Include database connection from config.php
include('config.php');
include ('sidebar.php')
;
// Initialize empty variable for form submission message
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient'];
    $next_appointment = $_POST['next_appointment'];
    $medications = $_POST['medication'];
    $doses = $_POST['dose'];
    $times = $_POST['time'];
    $days = $_POST['days'];
    $remarks = $_POST['remarks'];

    // Insert each medication entry into the prescriptions table
    foreach ($medications as $index => $medication) {
        $dose = $doses[$index];
        $time = $times[$index];
        $day = $days[$index];
        $remark = $remarks[$index];

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO medical_prescriptions (patient_id, dose, time, days, next_appointment, medication) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $patient_id, $dose, $time, $day, $next_appointment, $medication);
        
        if (!$stmt->execute()) {
            $message = "Error: " . $conn->error;
            break;
        }
    }

    if (!$message) {
        // Trigger SweetAlert for success
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Saved!",
                    text: "Prescription has been saved successfully.",
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>';
    }
}

// Fetch patients data for the dropdown
$patientsQuery = "SELECT patient_id, full_name, age, gender, address FROM patients";
$patientsResult = $conn->query($patientsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Prescription Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8ebe4; /* Soft beige background */
            color: #333; /* Neutral dark text color */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px; /* Wider layout */
            margin: 50px auto;
            padding: 30px;
            background-color: #fff; /* White container */
            border-radius: 20px; /* Rounded edges */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
        }

        h2 {
            color: #d87c8f; /* Feminine pink for the header */
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif; /* Elegant serif font */
            font-size: 1.8rem;
            text-align: center;
        }

        .header-image {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 15px; /* Soft rounded edges */
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            font-size: 1rem;
        }

        select, input[type="text"], input[type="date"] {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border-radius: 8px; /* Rounded inputs */
            border: 1px solid #e4d9d3; /* Soft beige border */
            font-size: 1rem;
            background-color: #fef9f7; /* Very light beige */
        }

        select:focus, input:focus {
            outline: none;
            border: 1px solid #d87c8f; /* Pink focus color */
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            gap: 10px; /* Space between sections */
        }

        .info-left, .info-right {
            flex: 1;
            font-size: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 1rem;
            background-color: #fef9f7; /* Very light beige table background */
            border-radius: 10px;
            overflow: hidden; /* Smooth table edges */
        }

        th {
            background-color: #d87c8f; /* Feminine pink for headers */
            color: #fff;
            padding: 12px;
            font-weight: bold;
            text-align: center;
        }

        td {
            padding: 10px;
            border: 1px solid #e4d9d3; /* Soft beige borders */
            text-align: center;
        }

        td input {
            width: 90%;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #e4d9d3;
            background-color: #fef9f7;
            font-size: 1rem;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 10px; /* Rounded buttons */
            cursor: pointer;
            font-size: 1rem;
            margin-top: 15px;
            width: 180px;
            font-weight: bold;
        }

        .save-btn {
            background-color: #d87c8f; /* Pink save button */
            color: white;
        }

        .save-btn:hover {
            background-color: #c36d7c; /* Darker pink hover */
        }

        .print-btn {
            background-color: #c8ad9e; /* Beige print button */
            color: white;
        }

        .print-btn:hover {
            background-color: #b89c8d; /* Darker beige hover */
        }

        .footer-section {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #6e6e6e; /* Soft gray for footer text */
        }

        .footer-section div {
            margin-bottom: 5px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="path/to/your/image.jpg" alt="Header Image" class="header-image">
        <h2>Medical Prescription</h2>

        <!-- Prescription Form -->
        <form id="prescriptionForm" method="POST">
            <label for="patient">Patient:</label>
            <select id="patient" name="patient" onchange="autoFillPatientInfo()" required>
                <option value="">Select Patient</option>
                <!-- PHP loop for patient dropdown -->
                <?php while ($patient = $patientsResult->fetch_assoc()): ?>
                    <option value="<?= $patient['patient_id'] ?>" 
                            data-age="<?= $patient['age'] ?>" 
                            data-gender="<?= $patient['gender'] ?>" 
                            data-address="<?= $patient['address'] ?>">
                        <?= $patient['full_name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <div class="info-section">
                <div class="info-left">
                    <label>Age: <span id="age"></span></label>
                </div>
                <div class="info-right">
                    <label>Gender: <span id="gender"></span></label>
                </div>
            </div>
            
            <div class="info-section">
                <div class="info-left">
                    <label>Address: <span id="address"></span></label>
                </div>
                <div class="info-right">
                    <label>Date: <span id="date"><?= date("Y-m-d") ?></span></label>
                </div>
            </div>

            <!-- Medication Table -->
            <table id="medicationTable">
                <tr>
                    <th>Medication</th>
                    <th>Dose</th>
                    <th>Times</th>
                    <th>Days</th>
                    <th>Remarks</th>
                </tr>
                <tr>
                    <td><input type="text" name="medication[]" placeholder="Medication" required></td>
                    <td><input type="text" name="dose[]" placeholder="Dose" required></td>
                    <td><input type="text" name="time[]" placeholder="Times (e.g., Morning, Night)" required></td>
                    <td><input type="text" name="days[]" placeholder="Days" required></td>
                    <td><input type="text" name="remarks[]" placeholder="Remarks"></td>
                </tr>
            </table>

            <div class="no-print">
                <button type="button" class="btn save-btn" onclick="addRow()">Add Row</button>
            </div>

            <label for="next_appointment">Next Appointment:</label>
            <input type="date" id="next_appointment" name="next_appointment" required>

            <div class="no-print" style="display: flex; gap: 20px; justify-content: center; margin-top: 20px;">
                <button type="submit" class="btn save-btn">Save Prescription</button>
                <button type="button" class="btn print-btn" onclick="window.print()">Print Prescription</button>
            </div>
            
            <div class="footer-section">
                <div>Doctor: Dr. Smith</div>
                <div>Lic No. 123124</div>
                <div>PTR No. 567890</div>
            </div>
        </form>
    </div>

    <script>
        function autoFillPatientInfo() {
            const patientSelect = document.getElementById('patient');
            const selectedOption = patientSelect.options[patientSelect.selectedIndex];

            document.getElementById('age').textContent = selectedOption.getAttribute('data-age');
            document.getElementById('gender').textContent = selectedOption.getAttribute('data-gender');
            document.getElementById('address').textContent = selectedOption.getAttribute('data-address');
        }

        function addRow() {
            const table = document.getElementById('medicationTable');
            const newRow = table.insertRow();
            newRow.innerHTML = `
                <td><input type="text" name="medication[]" placeholder="Medication" required></td>
                <td><input type="text" name="dose[]" placeholder="Dose" required></td>
                <td><input type="text" name="time[]" placeholder="Times (e.g., Morning, Night)" required></td>
                <td><input type="text" name="days[]" placeholder="Days" required></td>
                <td><input type="text" name="remarks[]" placeholder="Remarks"></td>
            `;
        }
    </script>
</body>
</html>
