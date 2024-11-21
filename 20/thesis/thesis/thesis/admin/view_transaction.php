<?php
// Include the database connection
include 'config.php';

// Ensure a date is provided
if (!isset($_GET['date']) || empty($_GET['date'])) {
    die("No date specified.");
}

$sale_date = $_GET['date'];

// Fetch transaction details for the given date, including product names
$query = "
    SELECT 
        sd.name AS customer_name,
        sd.discount_percent,
        p.product_name,
        sd.quantity,
        sd.subtotal
    FROM sales_details sd
    LEFT JOIN products p ON sd.product_id = p.id
    WHERE DATE(sd.sales_date) = ?
    ORDER BY sd.sales_date ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $sale_date);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch sales data
$sales = $result->fetch_all(MYSQLI_ASSOC);

// Calculate the overall total sales
$total_sales = 0;
foreach ($sales as $sale) {
    $total_sales += $sale['subtotal'];
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details</title>
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
            border-radius: 30px; /* Rounded edges */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow */
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
        }

        .total-row td {
            font-weight: bold;
            background-color: #fef1f5; /* Light pink for total row */
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
    <h2>Transaction Details for <?= htmlspecialchars($sale_date) ?></h2>

    <?php if (count($sales) > 0): ?>
        <!-- Transactions Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Discount (%)</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?= htmlspecialchars($sale['customer_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($sale['discount_percent'] ?? '0') ?></td>
                        <td><?= htmlspecialchars($sale['product_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($sale['quantity'] ?? '0') ?></td>
                        <td>₱<?= htmlspecialchars(number_format($sale['subtotal'], 2)) ?></td>
                    </tr>
                <?php endforeach; ?>
                <!-- Total Sales Row -->
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;">Overall Total:</td>
                    <td>₱<?= htmlspecialchars(number_format($total_sales, 2)) ?></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>No transactions found for this date.</p>
    <?php endif; ?>
</div>

<footer>
    &copy; <?= date('Y') ?> Transaction System. All rights reserved.
</footer>

</body>
</html>
