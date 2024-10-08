<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../../includes/db.php'); // Ensure this returns $mysqli

// Redirect if user is not logged in or not authorized
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'cashier' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit;
}

// Fetch products from the database
$query = "SELECT product_id, name, cost FROM products";
$result = $mysqli->query($query);

if ($result) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Database query failed: " . $mysqli->error;
    $products = [];
}

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
            <h1><img src="../../uploads/logosmaller.png" alt="Profile Image">FRESHMART</h1>
        </div>
        <ul>
            <li><a href="../index.php">Dashboard</a></li>
            <li><a href="../category.php">Category</a></li>
            <li><a href="../products.php">Products</a></li>
            <li><a href="">POS</a></li>
            <li><a href="../expired_goods.php">Expired Goods</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="time">
                <?php date_default_timezone_set('Africa/Nairobi');?>
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
                        <td colspan="2" id="grand-total">Ksh 0.00</td>
                    </tr>
                </tfoot>
            </table>
            <button id="make-sale">Make Sale</button>
        </section>
    </div>
    <script>$(document).ready(function() {
    const products = <?php echo json_encode($products); ?>;
    let itemCount = 0;
    let grandTotal = 0;

    $('#add-product').click(function() {
        const productName = $('#product-search').val().trim();
        const product = products.find(p => p.name.toLowerCase() === productName.toLowerCase());

        if (product) {
            itemCount++;
            const unitPrice = parseFloat(product.cost).toFixed(2);
            const quantity = 1;
            const subtotal = unitPrice * quantity;

            $('#sale-table tbody').append(`
                <tr data-item="${itemCount}">
                    <td>${itemCount}</td>
                    <td>${product.name}</td>
                    <td>Ksh ${unitPrice}</td>
                    <td><center><input type="number" value="${quantity}" min="1" class="quantity" data-price="${unitPrice}" style="width: 70px;"></center></td>
                    <td class="subtotal">Ksh ${subtotal.toFixed(2)}</td>
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
        const prevSubtotal = parseFloat(itemRow.find('.subtotal').text().replace('Ksh ', '').replace(',', ''));

        itemRow.find('.subtotal').text(`Ksh ${subtotal.toFixed(2)}`);
        updateGrandTotal(subtotal - prevSubtotal);
    });

    $(document).on('click', '.delete-item', function() {
        const itemRow = $(this).closest('tr');
        const subtotal = parseFloat(itemRow.find('.subtotal').text().replace('Ksh ', '').replace(',', ''));
        updateGrandTotal(-subtotal);
        itemRow.remove();
    });

    $('#make-sale').click(function() {
        const paymentMethod = $('#payment-method').val();
        const saleItems = [];

        $('#sale-table tbody tr').each(function() {
            const itemRow = $(this);
            const productName = itemRow.find('td:nth-child(2)').text();
            const unitPrice = parseFloat(itemRow.find('td:nth-child(3)').text().replace('Ksh ', '').replace(',', ''));
            const quantity = parseInt(itemRow.find('.quantity').val());
            const subtotal = parseFloat(itemRow.find('.subtotal').text().replace('Ksh ', '').replace(',', ''));

            saleItems.push({ productName, unitPrice, quantity, subtotal });
        });

        // Validate sale items and grand total
        if (saleItems.length === 0) {
            alert('No items in the sale.');
            return;
        }

        // Process sale based on payment method
        const requestData = {
            method_name: paymentMethod,
            sale_details: saleItems,
        };

        if (paymentMethod === 'cash') {
            const cashReceived = parseFloat(prompt('Enter cash received:'));
            if (isNaN(cashReceived) || cashReceived < grandTotal) {
                alert('Invalid amount. Please enter sufficient cash.');
                return;
            }
            requestData.cashReceived = cashReceived;
        } else if (paymentMethod === 'mpesa') {
            // Redirect to Mpesa payment page
            const saleAmount = grandTotal.toFixed(2);
            window.location.href = `mpesa-integration-php/index.php?amount=${saleAmount}`;
            return;
        }

        $.post('process_sale.php', requestData, function(response) {
            if (response.success) {
                if (paymentMethod === 'cash') {
                    const balance = response.balance || 0;
                    alert(`Sale completed successfully. Balance to give: Ksh ${balance.toFixed(2)}`);
                    // Redirect to receipt.php with parameters
                    window.location.href = `receipt.php?sale_id=${response.sale_id}&cash_received=${response.cash_received}&balance=${balance}`;
                } else {
                    alert('Sale completed successfully.');
                    // Redirect to receipt.php without cash_received and balance
                    window.location.href = `receipt.php?sale_id=${response.sale_id}`;
                }
            } else {
                alert('Sale failed. Please try again.');
            }
        }, 'json').fail(function(xhr, status, error) {
            console.error('Error:', status, error);
            alert('Sale failed. Please try again.');
        });
    });

    function updateGrandTotal(amount) {
        grandTotal += amount;
        $('#grand-total').text(`Ksh ${grandTotal.toFixed(2)}`);
    }
});


</script>
</body>
</html>
