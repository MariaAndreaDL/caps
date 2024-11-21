<?php
include 'config.php';

// Get product ID from the query string
if (!isset($_GET['product_id'])) {
    die("Invalid access. Product ID is required.");
}

$product_id = intval($_GET['product_id']);

// Fetch product information
$query = "SELECT product_name, category_id FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stock = intval($_POST['stock']);
    $expiration_date = $_POST['expiration_date'];

    // Fetch the current max batch_id for the product
    $batch_query = "SELECT MAX(batch_id) AS max_batch_id FROM products WHERE id = ?";
    $batch_stmt = $conn->prepare($batch_query);
    $batch_stmt->bind_param("i", $product_id);
    $batch_stmt->execute();
    $batch_result = $batch_stmt->get_result();
    $max_batch_id = $batch_result->fetch_assoc()['max_batch_id'] ?? 0;

    $new_batch_id = $max_batch_id + 1;

    // Insert the new batch into the products table
    $insert_query = "
        INSERT INTO products (product_name, stock, expiration_date, batch_id, category_id, status, date_added, updated_at) 
        VALUES (?, ?, ?, ?, ?, 'available', NOW(), NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sisii", $product['product_name'], $stock, $expiration_date, $new_batch_id, $product['category_id']);

    if ($insert_stmt->execute()) {
        header("Location: product_list.php?success=Batch added successfully");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Batch</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Batch for Product: <?php echo htmlspecialchars($product['product_name']); ?></h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="expiration_date">Expiration Date</label>
                <input type="date" name="expiration_date" id="expiration_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Batch</button>
            <a href="product_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
