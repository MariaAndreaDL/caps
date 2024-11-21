<?php
// Include database connection
include('config.php');

// Sort order option from GET (default to 'date')
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date';

// Filter type option from GET (default to 'all')
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'all';

// Create base queries for different types of prescriptions and certificates
$queries = [
    'cosmetics' => "SELECT 'Cosmetic Prescription' AS type, cp.cos_pres_id AS id, cp.issued_date, p.full_name 
                    FROM cosmetics_prescriptions cp 
                    JOIN patients p ON cp.patient_id = p.patient_id",
    'medical' => "SELECT 'Medical Prescription' AS type, mp.med_pres_id AS id, mp.issued_date, p.full_name 
                  FROM medical_prescriptions mp 
                  JOIN patients p ON mp.patient_id = p.patient_id",
    'certificates' => "SELECT 'Medical Certificate' AS type, mc.certificate_id AS id, mc.issued_date, p.full_name 
                       FROM medical_certificates mc 
                       JOIN patients p ON mc.patient_id = p.patient_id"
];

// Determine which queries to run based on filter type
$selected_queries = [];
if ($filter_type == 'all' || $filter_type == 'cosmetics') {
    $selected_queries[] = $queries['cosmetics'];
}
if ($filter_type == 'all' || $filter_type == 'medical') {
    $selected_queries[] = $queries['medical'];
}
if ($filter_type == 'all' || $filter_type == 'certificates') {
    $selected_queries[] = $queries['certificates'];
}

// Combine selected queries with UNION
$final_query = implode(' UNION ', $selected_queries);

// Add sorting to the final query
$order_by = $sort_by === 'date' ? 'ORDER BY issued_date DESC' : 'ORDER BY full_name ASC';
$final_query .= " $order_by";

// Run the final query
$results = $conn->query($final_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription and Certificate History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 0; }
        .container { width: 80%; margin: 0 auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #4a90e2; }
        .filters { text-align: center; margin-bottom: 20px; }
        select, button { padding: 10px; margin: 5px; font-size: 16px; border-radius: 4px; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #4a90e2; color: white; }
        td { background-color: #f9f9f9; }
    </style>
</head>
<body>

<div class="container">
    <h2>Prescription and Certificate History</h2>

    <!-- Filter and Sorting Options -->
    <div class="filters">
        <form method="GET" style="display: inline;">
            <!-- Filter by Type -->
            <label for="filter_type">Filter by Type:</label>
            <select name="filter_type" id="filter_type">
                <option value="all" <?= $filter_type == 'all' ? 'selected' : '' ?>>All</option>
                <option value="cosmetics" <?= $filter_type == 'cosmetics' ? 'selected' : '' ?>>Cosmetic Prescription</option>
                <option value="medical" <?= $filter_type == 'medical' ? 'selected' : '' ?>>Medical Prescription</option>
                <option value="certificates" <?= $filter_type == 'certificates' ? 'selected' : '' ?>>Medical Certificate</option>
            </select>

            <!-- Sort by Date or Alphabetically -->
            <label for="sort_by">Sort by:</label>
            <select name="sort_by" id="sort_by">
                <option value="date" <?= $sort_by == 'date' ? 'selected' : '' ?>>Date</option>
                <option value="alphabetical" <?= $sort_by == 'alphabetical' ? 'selected' : '' ?>>Alphabetical</option>
            </select>

            <button type="submit">Apply Filters</button>
        </form>
    </div>

    <!-- Display for All Prescriptions & Certificates -->
    <h3>All Prescription & Certificate History</h3>
    <table>
        <tr>
            <th>Type</th>
            <th>Patient Name</th>
            <th>Issued Date</th>
            <th>Action</th>
        </tr>
        <?php if ($results->num_rows > 0): ?>
            <?php while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['issued_date']) ?></td>
                    <td>
                        <a href="view_<?= strtolower(str_replace(' ', '_', $row['type'])) ?>.php?id=<?= $row['id'] ?>"><i class="fas fa-eye"></i> View</a> |
                        <a href="print_<?= strtolower(str_replace(' ', '_', $row['type'])) ?>.php?id=<?= $row['id'] ?>"><i class="fas fa-print"></i> Print</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align: center;">No records found</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
