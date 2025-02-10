<?php
include("./config.php");
session_start();

// Check if the cart exists and is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

// Retrieve the cart from the session
$cart = $_SESSION['cart'];

// Calculate the total price
$totalPrice = 0;
foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Connect to the database
connect();

// Insert the transaction into the `transactions` table
$user_id = $_SESSION['user_id'] ?? 1; // Assume user_id from session or a default value
$status = 'Completed'; // Assuming status is 'Completed'
$query = "INSERT INTO transactions (user_id, total_amount, transaction_date, status) 
          VALUES ('$user_id', '$totalPrice', NOW(), '$status')";
query($query);

// Get the last inserted transaction ID
$transaction_id = mysqli_insert_id($conn);

// Insert each product (item) into the `transaction_items` table
foreach ($cart as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];
    $price_per_unit = $item['price'];
    
    $query = "INSERT INTO transaction_items (transaction_id, product_id, quantity, price_per_unit) 
              VALUES ('$transaction_id', '$product_id', '$quantity', '$price_per_unit')";
    query($query);
}

// Clear the cart after generating the receipt
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You for Your Purchase</title>
    <!-- Include your CSS files or frameworks here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .receipt-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #e3e3e3;
            border-radius: 10px;
            background-color: #fff;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .receipt-header h1 {
            margin-bottom: 10px;
        }
        .receipt-details {
            margin-bottom: 20px;
        }
        .receipt-details p {
            margin: 5px 0;
        }
        .cart-table th, .cart-table td {
            text-align: center;
        }
        .redirect-button {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container receipt-container">
        <div class="receipt-header">
            <h1>Thank You for Your Purchase!</h1>
            <p>Your order has been successfully processed.</p>
        </div>

        <h3>Order Summary</h3>
        <table class="table table-bordered cart-table">
            <thead class="thead-dark">
                <tr>
                    <th>Product</th>
                    <th>Price (USD)</th>
                    <th>Quantity</th>
                    <th>Sub-total (USD)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td>$<?= number_format($item['price'], 2); ?></td>
                        <td><?= (int)$item['quantity']; ?></td>
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td><strong>$<?= number_format($totalPrice, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div class="redirect-button">
            <a href="products.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>

    <!-- Optional: Include Bootstrap JS for better styling -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
