<?php
// patient_list.php

include 'config.php';

// Initialize search and letter filter variables
$search = '';
$letter_filter = '';

// Capture search query and letter filter from POST or GET request
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}
if (isset($_GET['letter'])) {
    $letter_filter = $_GET['letter'];
}

// Base query to fetch patient records, with optional searching and letter filtering
$query = "SELECT * FROM patients";

// Build query based on search and letter filter
$params = [];
if (!empty($search)) {
    $query .= " WHERE full_name LIKE ?";
    $params[] = '%' . $search . '%';
} elseif (!empty($letter_filter)) {
    $query .= " WHERE full_name LIKE ?";
    $params[] = $letter_filter . '%';
}

$query .= " ORDER BY full_name ASC"; // Sort alphabetically

$stmt = $conn->prepare($query);

// Bind parameters if there are any
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f9fafc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin-top: 50px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4a90e2;
            font-weight: 500;
            text-align: center;
            margin-bottom: 20px;
        }
        .search-container, .alphabet-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-orange {
            background-color: #ff7f50;
            color: white;
            border: none;
            margin-left: 10px;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: 500;
        }
        .btn-orange:hover {
            background-color: #ff5722;
        }
        .alphabet-container .letter-btn {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
        }
        .alphabet-container .letter-btn:hover {
            background-color: #357abd;
        }
        .card {
            border: none;
            border-radius: 8px;
        }
        .card-body {
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background-color: #4a90e2;
            color: white;
        }
        th, td {
            padding: 15px;
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f1f3f7;
        }
        .action-buttons a {
            margin: 0 5px;
            padding: 5px 10px;
            font-size: 0.9em;
            font-weight: 500;
            border-radius: 5px;
            color: white;
        }
        .btn-view {
            background-color: #5cb85c;
        }
        .btn-view:hover {
            background-color: #4cae4c;
        }
        .btn-edit {
            background-color: #f0ad4e;
        }
        .btn-edit:hover {
            background-color: #ec971f;
        }
        .text-center {
            font-size: 1.1em;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Patient List</h1>

    <!-- Search Form -->
    <div class="search-container">
        <form method="POST" action="patient_list.php" class="form-inline">
            <input type="text" name="search" class="form-control" placeholder="Search by full name..." value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search" class="btn btn-orange">
        </form>
    </div>

    <!-- Alphabet Filter -->
    <div class="alphabet-container">
        <?php foreach (range('A', 'Z') as $letter): ?>
            <a href="patient_list.php?letter=<?php echo $letter; ?>" class="letter-btn"><?php echo $letter; ?></a>
        <?php endforeach; ?>
        <a href="patient_list.php" class="letter-btn">All</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Contact Number</th>
                        <th>Marital Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['marital_status']); ?></td>
                                <td class="action-buttons">
                                    <a href="view_patient_record.php?id=<?php echo $row['patient_id']; ?>" class="btn btn-view btn-sm">View</a> 
                                    <a href="edit_patient.php?id=<?php echo $row['patient_id']; ?>" class="btn btn-edit btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 Inventory System</p>
</footer>

<?php
// Close the connection
$stmt->close();
$conn->close();
?>
</body>
</html>
