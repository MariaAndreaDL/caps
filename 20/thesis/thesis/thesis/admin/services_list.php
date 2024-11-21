<?php
// Include the database connection
include 'config.php';

// Fetch services data with their category names
$services_result = $conn->query("
    SELECT s.service_id, s.service_name, sc.service_category_name 
    FROM services s
    JOIN services_category sc ON s.service_category_id = sc.service_category_id
    ORDER BY s.service_id ASC
");

if (!$services_result) {
    die("Query failed: " . $conn->error);
}

$services = $services_result->fetch_all(MYSQLI_ASSOC);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8ebe4; /* Beige background */
            color: #333;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
            max-width: 900px;
            padding: 20px;
            background-color: #fff; /* White content container */
            border-radius: 20px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        h2 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            color: #d87c8f; /* Soft pink */
        }

        .table {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden; /* Ensures the rounded corners apply to the table */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        th {
            background-color: #d87c8f; /* Pink header */
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 1rem;
        }

        td {
            padding: 15px;
            text-align: left;
            background-color: #fff; /* White for all rows */
            font-size: 1rem;
        }

        tr:nth-child(even) td {
            background-color: #fef9f7; /* Light beige for even rows */
        }

        tr:hover td {
            background-color: #ffe6eb; /* Light pink hover effect */
            cursor: pointer;
        }

        .add-service-btn {
            display: inline-block;
            margin: 20px auto;
            background-color: #d87c8f; /* Pink button */
            color: white;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 20px; /* Rounded button */
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .add-service-btn:hover {
            background-color: #c36d7c; /* Darker pink on hover */
            text-decoration: none;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Services List</h2>

    <!-- Add New Service Button -->
    <a href="add_service.php" class="add-service-btn">Add New Service</a>

    <table class="table">
        <thead>
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['service_id']) ?></td>
                        <td><?= htmlspecialchars($service['service_name']) ?></td>
                        <td><?= htmlspecialchars($service['service_category_name']) ?></td>
                        <td>
                            <a href="#" class="btn btn-danger" onclick="confirmDelete(event, <?= $service['service_id'] ?>); return false;">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No services found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<footer>
    &copy; <?= date('Y') ?> Services Management. All rights reserved.
</footer>

<script>
    function confirmDelete(event, serviceId) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'This service will be deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete_service.php?id=' + serviceId;
            }
        });
    }
</script>

</body>
</html>
