<!Doctype html>
<html lang="en">
<?php 
session_start();

// Initialize the cart session if not already set
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Handle actions for buttons + - X
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $index  = $_POST['product_id'] ?? '';
  $availableStock  = $_POST['product_stock'] ?? 10;
  

  if ($action === 'decrease' && isset($_SESSION['cart'][$index])) {
      if ($_SESSION['cart'][$index ]['quantity'] > 1) {
          $_SESSION['cart'][$index ]['quantity']--;
      } else {
          unset($_SESSION['cart'][$index ]);
      }
  } elseif ($action === 'increase' && isset($_SESSION['cart'][$index ])) {

    if ($_SESSION['cart'][$index ]['quantity'] < $availableStock) {
        $_SESSION['cart'][$index ]['quantity']++;
    } else {
        echo "<script>alert('Cannot add more. Stock limit reached!');</script>";
    }
  
    } elseif ($action === 'delete' && isset($_SESSION['cart'][$index ])) {
      unset($_SESSION['cart'][$index ]);
  }
}

// Retrieve updated cart data from the session
$cart = $_SESSION['cart'] ?? [];
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
  <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon">  
  <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">  
  <link rel="stylesheet" type="text/css" href="css/style2.css">  
  <title>Checkout</title>
  
</head>
<body class="bg-gray-100">
<?php 
   include("./navbar.php");
?>

<!-- strat wrapper -->
<div class="h-screen flex flex-row flex-wrap">

<?php 
   include("./sidebar.php");
?>


  <!-- start content -->
  <div class="bg-gray-100 flex-1 p-6 md:mt-16"> 
    <h1>Checkout</h1>
    <form action="https://sandbox.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_cart">
      <input type="hidden" name="upload" value="1">
      <input type="hidden" name="business" value="mohQ@business.example.com">
      <input type="hidden" name="currency_code" value="USD">

    <input type="hidden" name="return" value="http://localhost/cleopatra/dist/thank_you.php">
    <input type="hidden" name="cancel_return" value="http://localhost/cleopatra/dist/cancel.php">

    <table class="table table-bordered table-hover shadow-md rounded-lg">
        <thead class="thead-dark">
        <tr>
            <th class="text-center align-middle">Product</th>
            <th class="text-center align-middle">Price</th>
            <th class="text-center align-middle">Quantity</th>
            <th class="text-center align-middle">Sub-total</th>
            <th class="text-center align-middle">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        // print_r($cart);
        $totalPrice = 0;
        foreach ($cart as $index => $item): 
            // print_r($item);
            $subTotal = $item['price'] * $item['quantity'];
            $totalPrice += $subTotal;
        ?>
            <tr>
                <td class="align-middle text-center"><?= htmlspecialchars($item['name']); ?></td>
                <td class="align-middle text-center">$<?= number_format($item['price'], 2); ?></td>
                <td class="align-middle text-center"><?= $item['quantity']; ?></td>
                <td class="align-middle text-center">$<?= number_format($subTotal, 2); ?></td>
                <td class="align-middle text-center">
                    <form method="POST" action="" class="inline-block">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($index); ?>">
                        <input type="hidden" name="action" value="decrease">
                        <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded">-</button>
                    </form>
                    <form method="POST" action="" class="inline-block">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($index); ?>">
                        <input type="hidden" name="action" value="increase">
                        <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">+</button>
                    </form>
                    <form method="POST" action="" class="inline-block">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($index); ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">x</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php 
          // Add item fields for PayPal
          $i = 1;
          foreach ($cart as $item) {
              echo '<input type="hidden" name="item_number_' . $i . '" value="' . $i . '">';
              echo '<input type="hidden" name="item_name_' . $i . '" value="' . htmlspecialchars($item['name']) . '">';
              // Format the price as a proper decimal number for PayPal
              echo '<input type="hidden" name="amount_' . $i . '" value="' . number_format($item['price'], 2, '.', '') . '">';
              echo '<input type="hidden" name="qty_' . $i . '" value="' . (int)$item['quantity'] . '">';
              echo '<input type="hidden" name="quantity_' . $i . '" value="' . (int)$item['quantity'] . '">';
              $i++;
          }
        ?>


        <!-- Check if cart is empty -->
        <?php if (empty($cart)): ?>
            <tr>
                <td colspan="5" class="text-center text-gray-500">Your cart is empty.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-6">
        <h2 class="text-2xl font-semibold mb-4">Total: $<?= number_format($totalPrice, 2); ?></h2>
        <p class="text-lg text-gray-700">Items in Cart: <?= array_reduce($cart, function($totalItems, $item) {
            return $totalItems + $item['quantity'];
        }, 0); ?></p>
        <?php if (!empty($cart)): ?>
            <input type="image" name="submit" 
                src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" 
                alt="PayPal - The safer, easier way to pay online"
                class="mt-3 shadow-md rounded-lg border border-gray-300 hover:shadow-lg hover:opacity-90 transition duration-200">
        <?php else: ?>
            <p class="text-red-500 text-lg font-semibold">Add items to your cart to proceed with the checkout.</p>
        <?php endif; ?>
    </div>
</form>


  </div>
  <!-- end content -->

</div>
<!-- end wrapper -->

<!-- script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="js/scripts.js"></script>
<!-- end script -->

</body>
</html>

