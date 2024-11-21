<?php
// product_list.php
include 'sidebar.php'; 
include 'config.php';

// Fetch all products except archived ones
$product_query = "
    SELECT p.id, p.product_name, p.price, p.stock, p.expiration_date, c.category_name 
    FROM products p
    JOIN categories c ON p.category_id = c.category_id
    WHERE p.status != 'archived'
";
$product_result = $conn->query($product_query);

// Check for errors in the query execution
if (!$product_result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content" id="main-content">
        <h2>Product List</h2>
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="add_product.php" class="btn btn-success btn-sm">+ Add New Product</a>
            <a href="archived_product.php" class="btn btn-secondary btn-sm">View Archived Products</a>
            <input type="text" id="search-bar" class="form-control" placeholder="Search products..." style="width: 300px;">
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Expiration Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="product-list">
                <?php 
                $threshold_low_stock = 5;
                $expiration_warning_days = 7;
                $current_date = date('Y-m-d');

                while ($row = $product_result->fetch_assoc()) :
                    $status_class = '';
                    
                    // Determine status based on expiration date and stock
                    if (empty($row['expiration_date']) || $row['expiration_date'] == '0000-00-00') {
                        if ($row['stock'] > $threshold_low_stock) {
                            $status = 'Available';
                            $status_class = 'status-available';
                        } elseif ($row['stock'] > 0) {
                            $status = 'Low Stocks';
                            $status_class = 'status-low';
                        } else {
                            $status = 'Out of Stock';
                            $status_class = 'status-expired';
                        }
                    } else {
                        if ($row['expiration_date'] < $current_date) {
                            $status = 'Expired';
                            $status_class = 'status-expired';
                        } elseif (date('Y-m-d', strtotime($row['expiration_date'] . " -$expiration_warning_days days")) <= $current_date) {
                            $status = 'Close to Expiration';
                            $status_class = 'status-low';
                        } elseif ($row['stock'] > $threshold_low_stock) {
                            $status = 'Available';
                            $status_class = 'status-available';
                        } elseif ($row['stock'] > 0) {
                            $status = 'Low Stocks';
                            $status_class = 'status-low';
                        } else {
                            $status = 'Out of Stock';
                            $status_class = 'status-expired';
                        }
                    }
                ?>
                <tr class="product-row" data-product-id="<?php echo $row['id']; ?>">
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td>₱<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo $row['expiration_date'] ?: 'N/A'; ?></td>
                    <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
                    <td class="action-icons">
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>"><i class="fas fa-edit text-primary"></i></a>
                        <a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>); return false;"><i class="fas fa-trash-alt text-danger"></i></a>
                        <a href="archive_product.php?id=<?php echo $row['id']; ?>"><i class="fas fa-archive text-warning"></i></a>
                        <a href="add_batch.php?product_id=<?php echo $row['id']; ?>"><i class="fas fa-layer-group text-info"></i></a>
                    </td>
                </tr>
                <tr class="batch-row d-none" id="batch-row-<?php echo $row['id']; ?>">
                    <td colspan="7">
                        <div class="loading-spinner text-center">Loading batches...</div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            // Highlight row and fetch batches
            $('.product-row').click(function () {
                const productId = $(this).data('product-id');
                const batchRow = $(`#batch-row-${productId}`);

                // Toggle visibility and highlight
                batchRow.toggleClass('d-none');
                $(this).toggleClass('active-row');

                if (!batchRow.hasClass('loaded')) {
                    // Fetch batch data if not already loaded
                    $.ajax({
                        url: 'fetch_batches.php',
                        method: 'GET',
                        data: { product_id: productId },
                        success: function (response) {
                            batchRow.find('td').html(response);
                            batchRow.addClass('loaded');
                        },
                        error: function () {
                            batchRow.find('td').html('<div class="text-danger">Failed to load batches</div>');
                        }
                    });
                }
            });

            // SweetAlert for delete confirmation
            window.confirmDelete = function (productId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This product will be deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'delete_product.php?id=' + productId;
                    }
                });
            };

            // Search functionality
            $('#search-bar').on('keyup', function () {
                const searchValue = $(this).val().toLowerCase();
                $('#product-list tr').filter(function () {
                    $(this).toggle(
                        $(this).text().toLowerCase().indexOf(searchValue) > -1
                    );
                });
            });
        });
    </script>
</body>
</html>
