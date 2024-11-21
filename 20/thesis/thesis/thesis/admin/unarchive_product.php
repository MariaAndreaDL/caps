<?php
// unarchive_product.php

include 'config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Update the product's status to 'active'
    $unarchive_query = "UPDATE products SET status = 'active' WHERE id = ?";
    $stmt = $conn->prepare($unarchive_query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Show SweetAlert success message and redirect back to archived products list
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Unarchived!',
                    text: 'Product has been unarchived successfully.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'archived_product.php';
                });
              </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while unarchiving the product.',
                    showConfirmButton: true
                }).then(() => {
                    window.location.href = 'archived_product.php';
                });
              </script>";
    }

    $stmt->close();
}
$conn->close();
?>
