<?php
// add_product.php

// Include database connection
include 'config.php';

// Initialize variables
$errors = [];
$success_message = '';

// Fetch all categories for the dropdown
$categories = [];
$result = $conn->query("SELECT * FROM categories");
if ($result) {
    $categories = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $errors[] = "Error fetching categories.";
}

// Handle form submission for adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = trim($_POST['product_name']);
    $category_id = (int)$_POST['category_id'];
    $price = trim($_POST['price']);
    $stock = (int)$_POST['stock'];
    $expiration_date = trim($_POST['expiration_date']);

    // Validate input
    if (empty($product_name)) $errors[] = "Product name cannot be empty.";
    if (empty($category_id) || $category_id <= 0) $errors[] = "Invalid category selected.";
    if (empty($price) || !is_numeric($price)) $errors[] = "Price must be a valid number.";
    if (empty($stock) || !is_numeric($stock)) $errors[] = "Stock must be a valid number.";

    // Determine status based on stock and expiration date
    $status = '';
    if ($stock > 0) {
        $status = (!empty($expiration_date) && strtotime($expiration_date) < time()) ? 'expired' : 'available';
    } else {
        $status = 'out of stock';
    }

    // Insert new product if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products (product_name, category_id, price, stock, expiration_date, date_added, status) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("sidsss", $product_name, $category_id, $price, $stock, $expiration_date, $status);
        if ($stmt->execute()) {
            $success_message = "Product added successfully!";
            $product_name = $price = $stock = $expiration_date = '';
        } else {
            $errors[] = "Error adding product: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Body Styles */
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #fef6f6; /* Light Beige */
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #f3eae3; /* Soft Cream */
            color: #4d4d4d; /* Neutral Dark Gray */
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            font-size: 20px;
            font-weight: bold;
            background-color: #d3cbc6; /* Light Taupe */
            color: white;
            border-top-right-radius: 30px;
        }

        .sidebar-header .toggle-btn {
            font-size: 20px;
            cursor: pointer;
        }

        .sidebar ul {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #4d4d4d;
            padding: 12px 15px;
            border-radius: 12px;
            margin: 0 15px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a:hover,
        .sidebar ul li.active > a {
            background-color: #e6d6cf; /* Neutral Beige */
            color: #4d4d4d;
        }

        .sidebar ul li a .icon {
            font-size: 18px;
            margin-right: 12px;
            color: #a89b93; /* Soft Grayish Brown */
        }

        .sidebar ul li a .text {
            font-size: 16px;
        }

        .sidebar.collapsed ul li a {
            justify-content: center;
            padding: 15px;
        }

        .sidebar.collapsed ul li a .text {
            display: none;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px; /* Space for sidebar */
            padding: 30px;
            background-color: #fef6f6; /* Light Beige */
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed + .main-content {
            margin-left: 80px; /* Adjust for collapsed sidebar */
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #ffffff; /* White background */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Styling */
        form input, form select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #f27d9d; /* Coral Pink */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #d96280; /* Slightly darker Coral Pink */
        }

        .alert {
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <span>Product Management</span>
            <span class="toggle-btn"><i class="fas fa-bars"></i></span>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home icon"></i> <span class="text">Dashboard</span></a></li>
            <li><a href="product_list.php"><i class="fas fa-box icon"></i> <span class="text">Products</span></a></li>
            <li><a href="archived_products.php"><i class="fas fa-archive icon"></i> <span class="text">Archived Products</span></a></li>
            <li><a href="categories.php"><i class="fas fa-list icon"></i> <span class="text">Categories</span></a></li>
            <li><a href="users.php"><i class="fas fa-users icon"></i> <span class="text">User Management</span></a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="container">
            <h1>Add New Product</h1>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <p><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['category_id']; ?>" <?php echo (isset($category_id) && $category_id == $category['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($stock ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="expiration_date">Expiration Date</label>
                    <input type="date" id="expiration_date" name="expiration_date" value="<?php echo htmlspecialchars($expiration_date ?? ''); ?>">
                </div>
                <button type="submit" name="add_product">Add Product</button>
            </form>
        </div>
    </div>
    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.toggle-btn');
        const mainContent = document.querySelector('.main-content');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
