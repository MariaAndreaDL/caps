<?php
// archived_product.php
include 'sidebar.php'; 
include 'config.php';

// Fetch archived products
$archived_query = "
    SELECT p.id, p.product_name, p.price, p.stock, p.expiration_date, p.status, c.category_name 
    FROM products p
    JOIN categories c ON p.category_id = c.category_id
    WHERE p.status = 'archived'
";
$archived_result = $conn->query($archived_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* General Body Styling */
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #fef6f6;
            display: flex;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background-color: #f3eae3;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Main Content Styling */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #4d4d4d;
            font-weight: 600;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            text-align: center;
            padding: 15px;
            border: 1px solid #e0dede;
        }

        .table th {
            background-color: #f27d9d;
            color: white;
            font-weight: 600;
        }

        .table tbody tr:nth-child(even) {
            background-color: #fef3f5;
        }

        .table tbody tr:hover {
            background-color: #f7d4e3;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                display: none;
            }

            .btn-back {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <h2>Archived Products</h2>
            <a href="product_list.php" class="btn-back">Back to Product List</a>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $archived_result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td><?php echo number_format($row['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                <td><?php echo htmlspecialchars($row['expiration_date']); ?></td>
                                <td><?php echo ucfirst($row['status']); ?></td>
                                <td>
                                    <button onclick="confirmUnarchive(<?php echo $row['id']; ?>)" class="btn btn-success btn-sm">Unarchive</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmUnarchive(productId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to unarchive this product?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, unarchive it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'unarchive_product.php?id=' + productId;
                }
            });
        }
    </script>
<?php $conn->close(); ?>
</body>
</html>
