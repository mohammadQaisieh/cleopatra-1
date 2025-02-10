<?php
include("./config.php");
connect();

if (isset($_GET['transaction_id'])) {
    $transaction_id = intval($_GET['transaction_id']);

    // Fetch transaction details
    $query = "SELECT * FROM transactions WHERE transaction_id = $transaction_id";
    $transaction = mysqli_fetch_assoc(query($query));

    // Fetch transaction items
    $query = "SELECT ti.*, p.name AS product_name FROM transaction_items ti
              JOIN products p ON ti.product_id = p.id
              WHERE ti.transaction_id = $transaction_id";
    $items = query($query);

    if ($transaction) {
        echo "<p><strong>Transaction ID:</strong> " . htmlspecialchars($transaction['transaction_id']) . "</p>";
        echo "<p><strong>User ID:</strong> " . htmlspecialchars($transaction['user_id']) . "</p>";
        echo "<p><strong>Total Amount:</strong> $" . number_format($transaction['total_amount'], 2) . "</p>";
        echo "<p><strong>Date:</strong> " . htmlspecialchars($transaction['transaction_date']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($transaction['status']) . "</p>";
        echo "<h3 class='mt-4'>Items:</h3>";
        echo "<ul>";
        while ($item = mysqli_fetch_assoc($items)) {
            echo "<li>" . htmlspecialchars($item['product_name']) . " (x" . $item['quantity'] . ") - $" . number_format($item['price_per_unit'], 2) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Transaction not found.</p>";
    }
} else {
    echo "<p>Invalid transaction ID.</p>";
}
?>
