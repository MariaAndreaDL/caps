<?php
include 'config.php';

// Fetch patient details from the form submission
$patient_id = $_POST['patient_id'] ?? '';
$date = $_POST['date'] ?? '';
$product_ids = $_POST['product_id'] ?? [];
$prices = $_POST['price'] ?? [];
$quantities = $_POST['quantity'] ?? [];

// Function to fetch product name by `id` from the database
function getProductName($id) {
    global $conn; // Database connection

    $query = "SELECT product_name FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['product_name'];
    } else {
        return 'Unknown Product';
    }
}

// Fetch patient name from the database
function getPatientName($patient_id) {
    global $conn;

    $query = "SELECT full_name FROM patients WHERE patient_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['full_name'];
    } else {
        return 'Unknown Patient';
    }
}

$patient_name = getPatientName($patient_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table-container { margin-top: 20px; }
        #productTable th, #productTable td { text-align: center; }
        @media print {
            #printButton {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Receipt</h2>

    <!-- Patient and Date Info -->
    <div class="form-row mb-3">
        <div class="col">
            <label>Patient Name:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($patient_name); ?>" readonly>
        </div>
        <div class="col">
            <label>Date:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($date); ?>" readonly>
        </div>
    </div>

    <!-- Product Table -->
    <div class="table-container">
        <table class="table table-bordered" id="productTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($product_ids as $index => $product_id) {
                    $product_name = getProductName($product_id);
                    $price = $prices[$index];
                    $quantity = $quantities[$index];
                    $subtotal = $price * $quantity;
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($product_name); ?></td>
                    <td><?php echo number_format($price, 2); ?></td>
                    <td><?php echo htmlspecialchars($quantity); ?></td>
                    <td><?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Print Button -->
    <button id="printButton" class="btn btn-primary" onclick="window.print();">Print</button>
</div>
</body>
</html>
