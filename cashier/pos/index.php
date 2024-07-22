<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = include('../../includes/db.php'); // Assuming db.php returns $mysqli

// Redirect if user is not logged in or not authorized
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'cashier' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit;
}

// Fetch products from the database
$query = "SELECT product_id, name, price FROM products";
$result = $mysqli->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Sale - FRESHMART POS</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-image">
            <h1><img src="../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
        </div>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="category.php">Category</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="../pos/index.php">POS</a></li>
            <li><a href="../expiredgoods.php">Expired Goods</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="time">
                <?php echo date('l, F j, Y h:i A'); ?>
            </div>
            <div class="notifications">
                <a href="../notifications.php"><i class="fa fa-bell"></i></a>
            </div>
        </div>
        <section>
            <h2>POS Sale</h2>
            <div class="search-section">
                <label for="product-search">Search Product:</label>
                <input type="text" id="product-search" placeholder="Type product name">
                <button id="add-product">Add Product</button>
            </div>
            <div class="payment-section">
                <label for="payment-method">Payment Method:</label>
                <select id="payment-method">
                    <option value="cash">Cash</option>
                    <option value="mpesa">Mpesa</option>
                </select>
            </div>
            <table id="sale-table">
                <thead>
                    <tr>
                        <th>Item Number</th>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic rows go here -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Grand Total:</td>
                        <td colspan="2" id="grand-total">$0.00</td>
                    </tr>
                </tfoot>
            </table>
            <button id="make-sale">Make Sale</button>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            const products = <?php echo json_encode($products); ?>;
            let itemCount = 0;
            let grandTotal = 0;

            $('#add-product').click(function() {
                const productName = $('#product-search').val();
                const product = products.find(p => p.name.toLowerCase() === productName.toLowerCase());

                if (product) {
                    itemCount++;
                    const unitPrice = parseFloat(product.price).toFixed(2);
                    const quantity = 1;
                    const subtotal = unitPrice * quantity;

                    $('#sale-table tbody').append(`
                        <tr data-item="${itemCount}">
                            <td>${itemCount}</td>
                            <td>${product.name}</td>
                            <td>$${unitPrice}</td>
                            <td><input type="number" value="${quantity}" min="1" class="quantity" data-price="${unitPrice}"></td>
                            <td class="subtotal">$${subtotal.toFixed(2)}</td>
                            <td><button class="delete-item" data-item="${itemCount}">Delete</button></td>
                        </tr>
                    `);

                    updateGrandTotal(subtotal);
                } else {
                    alert('Product not found');
                }
            });

            $(document).on('input', '.quantity', function() {
                const quantity = $(this).val();
                const price = $(this).data('price');
                const itemRow = $(this).closest('tr');
                const subtotal = quantity * price;
                const prevSubtotal = parseFloat(itemRow.find('.subtotal').text().replace('$', ''));

                itemRow.find('.subtotal').text(`$${subtotal.toFixed(2)}`);
                updateGrandTotal(subtotal - prevSubtotal);
            });

            $(document).on('click', '.delete-item', function() {
                const itemRow = $(this).closest('tr');
                const subtotal = parseFloat(itemRow.find('.subtotal').text().replace('$', ''));
                updateGrandTotal(-subtotal);
                itemRow.remove();
            });

            $('#make-sale').click(function() {
                const paymentMethod = $('#payment-method').val();
                const saleItems = [];

                $('#sale-table tbody tr').each(function() {
                    const itemRow = $(this);
                    const productName = itemRow.find('td:nth-child(2)').text();
                    const unitPrice = parseFloat(itemRow.find('td:nth-child(3)').text().replace('$', ''));
                    const quantity = parseInt(itemRow.find('.quantity').val());
                    const subtotal = parseFloat(itemRow.find('.subtotal').text().replace('$', ''));

                    saleItems.push({ productName, unitPrice, quantity, subtotal });
                });

                // Additional handling for cash payment
                if (paymentMethod === 'cash') {
                    const cashReceived = parseFloat(prompt('Enter cash received:'));
                    if (isNaN(cashReceived) || cashReceived < grandTotal) {
                        alert('Invalid amount. Please enter sufficient cash.');
                        return;
                    }

                    $.post('process_sale.php', {
                        paymentMethod: method_name,
                        saleItems: sale_details,
                        cashReceived: cashReceived
                    }, function(response) {
                        if (response.success) {
                            const balance = response.balance || 0;
                            alert(`Sale completed successfully. Balance to give: $${balance.toFixed(2)}`);
                            window.location.href = `receipt.php?sale_id=${response.sale_id}`;
                        } else {
                            alert('Sale failed. Please try again.');
                        }
                    }, 'json');
                } 
                
                else {
                    $.post('process_sale.php', {
                        paymentMethod: method_name,
                        saleItems: sale_details
                    }, function(response) {
                        if (response.success) {
                            console.log(`Redirecting to receipt.php?sale_id=${response.sale_id}`);
                            window.location.href = `receipt.php?sale_id=${response.sale_id}`;
                        } else {
                            alert('Sale failed. Please try again.');
                        }
                    }, 'json');
                }
            });

            function updateGrandTotal(amount) {
                grandTotal += amount;
                $('#grand-total').text(`$${grandTotal.toFixed(2)}`);
            }
        });
    </script>

</body>
</html>
