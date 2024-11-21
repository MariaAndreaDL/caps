<?php
// Include the database connection
include 'config.php';
include 'sidebar.php';

// Set default sorting order and direction
$order_direction = "DESC"; // Default direction is descending
if (isset($_GET['order_direction']) && $_GET['order_direction'] == 'asc') {
    $order_direction = "ASC";
}

// Fetch sales grouped by date with the sum of subtotals
$sales_result = $conn->query("
    SELECT DATE(sd.sales_date) AS sale_date, SUM(sd.subtotal) AS total_subtotal
    FROM sales_details sd
    GROUP BY DATE(sd.sales_date)
    ORDER BY sale_date $order_direction
");

if (!$sales_result) {
    die("Query failed: " . $conn->error);
}

// Fetch sales data
$sales = $sales_result->fetch_all(MYSQLI_ASSOC);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales History</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
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
            padding: 30px;
            background-color: #fff; /* White content container */
            border-radius: 30px; /* Soft rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Soft shadow */
        }

        h2 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            color: #d87c8f; /* Feminine pink for the title */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 20px; /* Rounded table */
            overflow: hidden; /* Prevent sharp edges on table */
        }

        th {
            background-color: #d87c8f; /* Pink header background */
            color: #fff;
            text-align: left;
            padding: 15px;
            font-size: 1rem;
        }

        td {
            padding: 15px;
            text-align: left;
            font-size: 1rem;
            background-color: #fef9f7; /* Light beige for all rows */
        }

        tr:nth-child(even) td {
            background-color: #fff; /* White for even rows */
        }

        tr:hover td {
            background-color: #ffe6eb; /* Light pink hover effect */
            cursor: pointer;
        }

        select {
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 15px;
            background-color: #fef9f7;
            margin-bottom: 20px;
            outline: none;
            transition: background-color 0.3s ease;
        }

        select:focus {
            background-color: #ffe6eb; /* Pink focus on dropdown */
        }

        label {
            font-weight: 500;
            margin-right: 10px;
        }

        /* Footer text styling */
        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }

        /* Remove sharp edges from the scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f8ebe4; /* Match beige background */
        }

        ::-webkit-scrollbar-thumb {
            background: #d87c8f; /* Pink scrollbar */
            border-radius: 5px; /* Rounded scrollbar */
        }
    </style>
    <script>
        function viewTransaction(date) {
            window.location.href = `view_transaction.php?date=${date}`;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Sales History</h2>

    <!-- Sort Dropdown with Order Direction Toggle -->
    <form method="get" class="mb-3 text-center">
        <label for="order_direction">Sort by:</label>
        <select name="order_direction" id="order_direction" onchange="this.form.submit()">
            <option value="desc" <?= isset($_GET['order_direction']) && $_GET['order_direction'] == 'desc' ? 'selected' : '' ?>>Descending</option>
            <option value="asc" <?= isset($_GET['order_direction']) && $_GET['order_direction'] == 'asc' ? 'selected' : '' ?>>Ascending</option>
        </select>
    </form>

    <!-- Sales Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Date of Sale</th>
                <th>Total Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr onclick="viewTransaction('<?= htmlspecialchars($sale['sale_date']) ?>')">
                    <td><?= htmlspecialchars(date('Y-m-d', strtotime($sale['sale_date']))) ?></td>
                    <td>₱<?= htmlspecialchars(number_format($sale['total_subtotal'], 2)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer>
    &copy; <?= date('Y') ?> Sales History System. All rights reserved.
</footer>

</body>
</html>
