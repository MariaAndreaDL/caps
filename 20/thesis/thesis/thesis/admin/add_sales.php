<?php
// add_sales.php

include 'config.php';
include 'sidebar.php'; 
include 'styles.css';

// Fetch products
$product_query = "SELECT id, product_name, price, stock FROM products";
$product_result = $conn->query($product_query);

// Store product data in an array for autocomplete
$products = [];
while ($row = $product_result->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drolah Sales</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            display: flex;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }



        
        #productTable th,
        #productTable td {
            text-align: center;
        }
    </style>
</head>
<body>
   
    <div class="content">
        <div class="container mt-5">
            <h2 class="text-center">Drolah Sales</h2>

            <!-- Name and Date Input -->
            <form id="salesForm">
                <div class="form-row mb-3">
                    <div class="col">
                        <label for="nameInput">Name</label>
                        <input type="text" class="form-control" id="nameInput" name="name" placeholder="Enter name (optional)">
                    </div>
                    <div class="col">
                        <label for="date">Date</label>
                        <input type="text" class="form-control" id="date" name="date" readonly>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productRows">
                            <!-- Rows will be added dynamically here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">Total:</td>
                                <td id="totalPrice">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">Discount (%):</td>
                                <td>
                                    <input type="number" id="discountPercent" name="discount_percent" class="form-control" placeholder="Enter discount %" min="0" max="100">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info" id="applyDiscount">Apply</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">Discounted Total:</td>
                                <td id="discountedTotal">0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Add Product and Submit Buttons -->
                <div class="form-row">
                    <button type="button" class="btn btn-primary mr-2" id="addProduct">Add Product</button>
                    <button type="button" class="btn btn-success" id="submitSale">Submit Sales</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const products = <?php echo json_encode($products); ?>;

        function addProductRow() {
            const row = document.createElement('tr');

            let productOptions = '<option value="">Select Product</option>';
            products.forEach(product => {
                productOptions += `<option value="${product.id}" data-price="${product.price}" data-stock="${product.stock}">${product.product_name}</option>`;
            });

            row.innerHTML = `
                <td>
                    <select class="form-control" name="product_id[]" onchange="setProductPrice(this)">
                        ${productOptions}
                    </select>
                </td>
                <td><input type="text" class="form-control" name="price[]" readonly></td>
                <td><input type="number" class="form-control" name="quantity[]" min="1" oninput="updateSubtotal(this)"></td>
                <td><input type="text" class="form-control" name="subtotal[]" readonly></td>
                <td><button type="button" class="btn btn-danger" onclick="removeProductRow(this)">Remove</button></td>
            `;

            document.getElementById('productRows').appendChild(row);
        }

        document.getElementById('addProduct').addEventListener('click', addProductRow);

        function setProductPrice(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            const row = selectElement.closest('tr');
            row.querySelector('input[name="price[]"]').value = parseFloat(price).toFixed(2);
            updateSubtotal(row.querySelector('input[name="quantity[]"]'));
        }

        function updateSubtotal(input) {
            const row = input.closest('tr');
            const price = parseFloat(row.querySelector('input[name="price[]"]').value) || 0;
            const quantity = parseInt(input.value) || 0;
            const selectedOption = row.querySelector('select').selectedOptions[0];
            const stock = parseInt(selectedOption.getAttribute('data-stock'));

            if (quantity > stock) {
                Swal.fire("Not enough stocks for this", "", "warning");
                input.value = stock;
            }

            const subtotal = (price * quantity).toFixed(2);
            row.querySelector('input[name="subtotal[]"]').value = subtotal;
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('input[name="subtotal[]"]').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('totalPrice').textContent = total.toFixed(2);
            calculateDiscountedTotal();
        }

        function calculateDiscountedTotal() {
            const total = parseFloat(document.getElementById('totalPrice').textContent) || 0;
            const discountPercent = parseFloat(document.getElementById('discountPercent').value) || 0;
            const discount = (total * discountPercent) / 100;
            const discountedTotal = total - discount;
            document.getElementById('discountedTotal').textContent = discountedTotal.toFixed(2);
        }

        document.getElementById('applyDiscount').addEventListener('click', calculateDiscountedTotal);

        function removeProductRow(button) {
            button.closest('tr').remove();
            calculateTotal();
        }

        $('#submitSale').click(function () {
            const formData = $('#salesForm').serialize();

            if ($('#productRows tr').length === 0) {
                Swal.fire("At least one product is required", "", "warning");
                return;
            }

            let valid = true;
            $('#productRows select, #productRows input[name="quantity[]"]').each(function () {
                if (!$(this).val()) {
                    Swal.fire("All fields must be filled", "", "warning");
                    valid = false;
                    return false;
                }
            });

            if (!valid) return;

            $.ajax({
                type: 'POST',
                url: 'add_sales_to_db.php',
                data: formData,
                success: function () {
                    window.location.href = 'sales_history.php';
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error: " + error);
                }
            });
        });
    </script>
</body>
</html>
