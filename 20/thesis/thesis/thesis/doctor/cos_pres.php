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
    $products = $_POST['product'];
    $remarks = $_POST['remarks'];
    $next_appointment = $_POST['next_appointment']; // Get the next appointment date

    // Insert each product entry into the cosmetics_prescriptions table
    foreach ($products as $index => $product) {
        $remark = $remarks[$index];

        $sql = "INSERT INTO cosmetics_prescriptions (patient_id, product, remarks, next_appointment) 
                VALUES ('$patient_id', '$product', '$remark', '$next_appointment')";
        if (!$conn->query($sql)) {
            $message = "Error: " . $conn->error;
            break;
        }
    }

    if (!$message) {
        $message = "Cosmetic Prescription saved successfully.";
        $show_alert = true; // Flag to trigger SweetAlert
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
    <title>Cosmetics Prescription Form</title>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8ebe4; /* Soft beige */
            color: #333; /* Neutral dark text */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff; /* White container */
            border-radius: 20px; /* Rounded edges */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        h2 {
            color: #d87c8f; /* Feminine pink */
            font-family: 'Playfair Display', serif; /* Elegant serif font */
            font-size: 1.8rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .header-image {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 15px; /* Rounded edges */
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
            margin: 10px 0;
            border-radius: 8px; /* Rounded inputs */
            border: 1px solid #e4d9d3; /* Soft beige border */
            background-color: #fef9f7; /* Light beige */
            font-size: 1rem;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #d87c8f; /* Pink border focus */
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            gap: 10px;
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
            background-color: #fef9f7; /* Light beige */
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #d87c8f; /* Pink for header */
            color: white;
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
            font-size: 1rem;
            margin-top: 15px;
            cursor: pointer;
            width: 180px;
            font-weight: bold;
        }

        .save-btn {
            background-color: #d87c8f; /* Pink */
            color: white;
        }

        .save-btn:hover {
            background-color: #c36d7c; /* Darker pink hover */
        }

        .print-btn {
            background-color: #c8ad9e; /* Beige */
            color: white;
        }

        .print-btn:hover {
            background-color: #b89c8d; /* Darker beige hover */
        }

        .footer-section {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #6e6e6e; /* Soft gray */
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
    <h2>Cosmetics Prescription</h2>

    <!-- Display Success Message -->
    <?php if ($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form id="cosmeticsPrescriptionForm" method="POST">
        <label for="patient">Patient:</label>
        <select id="patient" name="patient" onchange="autoFillPatientInfo()">
            <option value="">Select Patient</option>
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

        <table id="productTable">
            <tr>
                <th>Product</th>
                <th>Remarks</th>
            </tr>
            <tr>
                <td><input type="text" name="product[]" placeholder="Product"></td>
                <td><input type="text" name="remarks[]" placeholder="Remarks"></td>
            </tr>
        </table>

        <div class="no-print">
            <button type="button" class="btn save-btn" onclick="addRow()">Add Row</button>
        </div>

        <label for="next_appointment">Next Appointment Date:</label>
        <input type="date" id="next_appointment" name="next_appointment" required>

        <div class="no-print" style="display: flex; gap: 20px; justify-content: center; margin-top: 20px;">
            <button type="submit" class="btn save-btn">Save Prescription</button>
            <button type="button" onclick="window.print()" class="btn print-btn">Print Prescription</button>
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
        const patientInfo = patientSelect.options[patientSelect.selectedIndex];
        document.getElementById('age').textContent = patientInfo.getAttribute('data-age');
        document.getElementById('gender').textContent = patientInfo.getAttribute('data-gender');
        document.getElementById('address').textContent = patientInfo.getAttribute('data-address');
    }

    function addRow() {
        const table = document.getElementById('productTable');
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td><input type="text" name="product[]" placeholder="Product"></td>
            <td><input type="text" name="remarks[]" placeholder="Remarks"></td>
        `;
    }

    <?php if (isset($show_alert) && $show_alert): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Cosmetics Prescription has been saved successfully.',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>

</body>
</html>
