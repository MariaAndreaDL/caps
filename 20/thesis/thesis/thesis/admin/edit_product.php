<?php
// edit_product.php

include 'config.php'; // Database connection

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Change to 'id' to match the URL key

    // Fetch product details
    $product_query = "
        SELECT p.id, p.product_name, p.price, p.stock, p.expiration_date, p.status, p.category_id, c.category_name
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        WHERE p.id = $id
    ";
    $product_result = $conn->query($product_query);

    // Check if the product exists
    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}

// Fetch categories for the dropdown
$category_query = "SELECT category_id, category_name FROM categories";
$category_result = $conn->query($category_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $expiration_date = $_POST['expiration_date'];
    $status = $_POST['status'];
    $category_id = $_POST['category_id'];

    // Update product in the database
    $update_query = "
        UPDATE products 
        SET product_name = '$product_name', price = '$price', stock = '$stock', expiration_date = '$expiration_date', 
            status = '$status', category_id = '$category_id'
        WHERE id = $id
    ";

    if ($conn->query($update_query) === TRUE) {
        echo "Product updated successfully.";
        header("Location: product_list.php");
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>

        <form action="" method="POST">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category_id" required>
                    <?php while ($row = $category_result->fetch_assoc()) : ?>
                        <option value="<?php echo $row['category_id']; ?>" <?php if ($row['category_id'] == $product['category_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
            </div>

            <div class="form-group">
                <label for="expiration_date">Expiration Date:</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="<?php echo htmlspecialchars($product['expiration_date']); ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" <?php if ($product['status'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($product['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                    <option value="discontinued" <?php if ($product['status'] == 'discontinued') echo 'selected'; ?>>Discontinued</option>
                    <option value="available" <?php if ($product['status'] == 'available') echo 'selected'; ?>>Available</option>
                    <option value="out of stock" <?php if ($product['status'] == 'out of stock') echo 'selected'; ?>>Out of Stock</option>
                    <option value="close to expiration" <?php if ($product['status'] == 'close to expiration') echo 'selected'; ?>>Close to Expiration</option>
                    <option value="expired" <?php if ($product['status'] == 'expired') echo 'selected'; ?>>Expired</option>
                </select>
            </div>


            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>

<?php $conn->close(); ?>

</body>
</html>
