<?php
// archive_product.php

include 'config.php';
include 'sidebar.php'; 

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Update the product's status to 'archived'
    $archive_query = "UPDATE products SET status = 'archived' WHERE id = ?";
    $stmt = $conn->prepare($archive_query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Redirect back to product list with a success message using JavaScript
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Archived!',
                    text: 'Product has been archived successfully.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'product_list.php';
                });
              </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while archiving the product.',
                    showConfirmButton: true
                }).then(() => {
                    window.location.href = 'product_list.php';
                });
              </script>";
    }

    $stmt->close();
}
$conn->close();
?>
