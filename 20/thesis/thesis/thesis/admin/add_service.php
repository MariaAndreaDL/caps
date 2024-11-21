<?php
// Include database connection
include 'config.php';

// Fetch service categories to populate the dropdown
$categories_result = $conn->query("SELECT service_category_id, service_category_name FROM services_category");

if (!$categories_result) {
    die("Query failed: " . $conn->error);
}

// Initialize a variable to track if the service was added successfully
$service_added = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = $_POST['service_name'];
    $service_category_id = $_POST['service_category_id'];

    // Insert new service into the 'services' table
    $insert_query = $conn->prepare("INSERT INTO services (service_name, service_category_id) VALUES (?, ?)");
    $insert_query->bind_param("si", $service_name, $service_category_id);

    if ($insert_query->execute()) {
        $service_added = true; // Set to true if the service was added successfully
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the prepared statement
    $insert_query->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f4f4f4;
            font-family: 'Montserrat', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #ff8c00;
            border-color: #ff8c00;
        }

        .btn-primary:hover {
            background-color: #e07b00;
            border-color: #e07b00;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Service</h2>
    <div class="form-container">
        <form method="POST" action="">
            <div class="form-group">
                <label for="service_name">Service Name</label>
                <input type="text" class="form-control" id="service_name" name="service_name" required>
            </div>

            <div class="form-group">
                <label for="service_category_id">Service Category</label>
                <select class="form-control" id="service_category_id" name="service_category_id" required>
                    <option value="">Select Category</option>
                    <?php while ($row = $categories_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['service_category_id']) ?>">
                            <?= htmlspecialchars($row['service_category_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Service</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Check if the service was added successfully
        <?php if ($service_added): ?>
            Swal.fire({
                title: 'Success!',
                text: 'New service added successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    });
</script>

</body>
</html>
