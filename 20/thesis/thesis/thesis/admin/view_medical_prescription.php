<?php
// Include database configuration
include 'config.php';

// Get the prescription ID from the URL
$prescription_id = $_GET['id'];

// Fetch prescription details
$query = "SELECT mp.*, p.full_name, p.age, p.gender, p.address 
          FROM medical_prescriptions mp 
          JOIN patients p ON mp.patient_id = p.patient_id 
          WHERE mp.med_pres_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $prescription_id);
$stmt->execute();
$result = $stmt->get_result();
$prescription = $result->fetch_assoc();

if (!$prescription) {
    die("Prescription not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Prescription - View</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print { button { display: none; } }
        body { font-family: Arial, sans-serif; background-color: #f8fafc; display: flex; justify-content: center; min-height: 100vh; }
        .container { max-width: 800px; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
        .header-image { width: 100%; max-height: 150px; object-fit: cover; margin-bottom: 15px; border-radius: 8px; }
        .footer-section { text-align: center; font-size: 12px; color: #718096; margin-top: 15px; }
    </style>
</head>
<body>

<div class="container">
    <img src="path/to/your/image.jpg" alt="Header Image" class="header-image">
    <h2 class="text-center">Medical Prescription</h2>

    <p><strong>Patient Name:</strong> <?= htmlspecialchars($prescription['full_name']) ?></p>
    <p><strong>Age:</strong> <?= htmlspecialchars($prescription['age']) ?></p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($prescription['gender']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($prescription['address']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($prescription['issued_date']) ?></p>

    <table class="table mt-4">
        <tr>
            <th>Medication</th>
            <th>Dose</th>
            <th>Time</th>
            <th>Days</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td><?= htmlspecialchars($prescription['medication']) ?></td>
            <td><?= htmlspecialchars($prescription['dose']) ?></td>
            <td><?= htmlspecialchars($prescription['time']) ?></td>
            <td><?= htmlspecialchars($prescription['days']) ?></td>
            <td><?= htmlspecialchars($prescription['remarks']) ?></td>
        </tr>
    </table>

    <div class="footer-section">
        <p>Doctor: Dr. Smith</p>
        <p>Lic No. 123124</p>
        <p>PTR No. 567890</p>
    </div>

    <button onclick="window.print()" class="btn btn-secondary mt-3">Print Prescription</button>
</div>

</body>
</html>
