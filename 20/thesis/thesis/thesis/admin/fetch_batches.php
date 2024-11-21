<?php
include 'config.php';

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Fetch batches for the product
    $query = "
        SELECT 
            batch_id, 
            stock AS batch_stock, 
            expiration_date,
            CASE 
                WHEN stock = 0 THEN 'done'
                ELSE 'available'
            END AS batch_status
        FROM products
        WHERE id = $product_id
        ORDER BY batch_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<table class="table">';
        echo '<thead><tr><th>Batch ID</th><th>Stock</th><th>Expiration Date</th><th>Status</th></tr></thead>';
        echo '<tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['batch_id'] . '</td>';
            echo '<td>' . $row['batch_stock'] . '</td>';
            echo '<td>' . $row['expiration_date'] . '</td>';
            echo '<td>' . ucfirst($row['batch_status']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<span class="text-warning">No batches found for this product.</span>';
    }
} else {
    echo '<span class="text-danger">Invalid product ID.</span>';
}
?>
