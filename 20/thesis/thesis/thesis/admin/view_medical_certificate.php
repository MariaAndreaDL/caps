<?php
// Include your database configuration
include 'config.php';

// Get the certificate ID from the URL
$certificate_id = $_GET['id'];

// Fetch certificate details from the database
$query = "SELECT mc.*, p.full_name, p.age, p.gender, p.marital_status, p.address 
          FROM medical_certificates mc 
          JOIN patients p ON mc.patient_id = p.patient_id 
          WHERE mc.certificate_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $certificate_id);
$stmt->execute();
$result = $stmt->get_result();
$certificate = $result->fetch_assoc();

if (!$certificate) {
    die("Certificate not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate - View</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print { button { display: none; } }
        body { font-family: Arial, sans-serif; background-color: #f0f8ff; display: flex; justify-content: center; min-height: 100vh; }
        .certificate-container { width: 70%; padding: 30px; border: 2px solid #333; border-radius: 15px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin: 20px; }
        .header, .footer { text-align: center; font-weight: bold; }
        .header img { width: 120px; height: 120px; margin-bottom: 10px; border: 2px solid #ddd; border-radius: 50%; }
        .content-section { font-size: 1.2em; margin-top: 20px; }
    </style>
</head>
<body>

<div class="certificate-container">
    <div class="header">
        <img src="path_to_placeholder_image.png" alt="Patient's Picture">
        <h2>Medical Certificate</h2>
    </div>
    
    <div class="content-section">
        <p>Date: <?= htmlspecialchars($certificate['issued_date']) ?></p>
        <p>
            This is to certify that <?= htmlspecialchars($certificate['full_name']) ?>, 
            Age <?= htmlspecialchars($certificate['age']) ?>, 
            Sex <?= htmlspecialchars($certificate['gender']) ?>, 
            Civil Status <?= htmlspecialchars($certificate['marital_status']) ?>, 
            residing at <?= htmlspecialchars($certificate['address']) ?>, 
            has been under my medical care for the following medical condition(s):
        </p>
        
        <p><strong>Medical Conditions:</strong><br><?= nl2br(htmlspecialchars($certificate['medical_conditions'])) ?></p>
        <p><strong>Remarks:</strong><br><?= nl2br(htmlspecialchars($certificate['remarks'])) ?></p>
        
        <p>This certification is being issued upon request to be used exclusively for medical purposes.</p>
    </div>
    
    <div class="footer">
        <p>Doctor 1234342</p>
        <p>Lic No. 123124</p>
        <p>PTR No.</p>
    </div>
    
    <button onclick="window.print()" class="btn btn-secondary mt-3">Print Certificate</button>
</div>

</body>
</html>
