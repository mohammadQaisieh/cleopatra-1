<?php
include("./config.php");
session_start();

// Initialize the cart session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product = [
        'id' => $_POST['product_id'],
        'name' => $_POST['product_name'],
        'price' => $_POST['product_price'],
        'stock' => $_POST['product_stock'],
        'quantity' => 1,
    ];

    // Check if the product already exists in the cart
    $exists = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['id'] == $product['id']) {
            $cartItem['quantity']++;
            $exists = true;
            break;
        }
    }

    // Add the product to the cart if it doesn't exist
    if (!$exists) {
        $_SESSION['cart'][] = $product;
    }
}
?>

<!doctype html>
<html lang="en">
<head>

   

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
  <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon">  
  <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">  
  <title>Welcome To Products</title>
  <style>
      /* Adjust the width and layout of the wrapper and product grid */
      .wrapper {
          display: flex;
          flex-direction: row;
          flex-wrap: nowrap;
          height: 100vh;
          width: 100%;
      }
      .content {
          flex: 1;
          overflow-y: auto;
          padding: 10px;
      }
      .filter-container {
          max-width: 300px;
          margin-bottom: 20px;
      }
      .product-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
          gap: 1.5rem;
          padding: 1rem;
      }
      .product-card {
          background-color: #fff;
          border-radius: 8px;
          box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
          overflow: hidden;
      }
      .product-card h2 {
          font-size: 1.25rem;
          margin-bottom: 0.5rem;
      }
      .product-card p {
          margin: 0.5rem 0;
      }
  </style>
</head>

<body class="bg-gray-100">
<?php 
   include("./navbar.php");
?>

<!-- Wrapper -->
<div class="wrapper">
   
<?php 
   include("./sidebar.php");
?>
<div class="content bg-gray-100">

    <!-- Filter Menu -->
    <div class="filter-container p-4 bg-white shadow-md rounded-lg">
        <h2 class="text-xl font-semibold mb-3">Filter Products</h2>
        <form id="filterForm" method="GET" action="">
            <label class="block mb-2">
                <span class="text-gray-700">Category</span>
                <select name="category" class="form-select mt-1 block w-full">
                    <option value="">All Categories</option>
                    <option value="category1">Category 1</option>
                    <option value="category2">Category 2</option>
                    <option value="category3">Category 3</option>
                </select>
            </label>
            <label class="block mb-2">
                <span class="text-gray-700">Price Range</span>
                <select name="price" class="form-select mt-1 block w-full">
                    <option value="">All Prices</option>
                    <option value="0-20">$0 - $20</option>
                    <option value="20-50">$20 - $50</option>
                    <option value="50-100">$50 - $100</option>
                </select>
            </label>
            <!-- <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Apply Filters</button> -->
            <div class="flex justify-between items-center mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-2 py-2 rounded">Apply Filters</button>
                    <a href="checkout.php" class="bg-green-500 text-white px-2 py-2 rounded">Go to Checkout</a>
                </div>
        </form>
    </div>

    <!-- Products Section -->
    <h1 class="text-3xl font-bold mb-8 text-center">Our Products</h1>

    <div class="product-grid">
        
    <?php
// Connect to the database
connect();
$result = query('SELECT * FROM products;');

// Fetch all rows as an associative array
$allProducts = [];
while ($row = $result->fetch_assoc()) {
    $allProducts[] = $row;
}

// Capture filter parameters  8
$categoryFilter = $_GET['category'] ?? '';
$priceFilter = $_GET['price'] ?? '';

// Filter products based on selection
$filteredProducts = array_filter($allProducts, function ($product) use ($categoryFilter, $priceFilter) {
    $matchesCategory = !$categoryFilter || $product['category'] === $categoryFilter;
    $matchesPrice = true;
    if ($priceFilter) {
        list($minPrice, $maxPrice) = explode('-', $priceFilter);
        $matchesPrice = $product['price'] >= $minPrice && $product['price'] <= $maxPrice;
    }
    return $matchesCategory && $matchesPrice;
});




// Loop through filtered products and display them
foreach ($filteredProducts as $product) {
    echo '
    <div class="product-card p-5">
        <h2 class="text-xl font-semibold mb-3">' . htmlspecialchars($product['name']) . '</h2>
        <p class="text-gray-600 mb-4">' . htmlspecialchars($product['description']) . '</p>
        <p class="text-lg font-bold text-indigo-600">$' . number_format($product['price'], 2) . '</p>
        <form method="POST" action="">
            <input type="hidden" name="product_id" value="' . htmlspecialchars($product['id']) . '">
            <input type="hidden" name="product_name" value="' . htmlspecialchars($product['name']) . '">
            <input type="hidden" name="product_price" value="' . htmlspecialchars($product['price']) . '">
            <input type="hidden" name="product_stock" value="' . htmlspecialchars($product['stock']) . '">
            <button type="submit" name="add_to_cart" class="bg-green-500 text-white px-4 py-2 rounded">Add to Cart</button>
        </form>
    </div>
    ';
}


?>


    </div>
</div>
<!-- end content -->
</div>
<!-- End Wrapper -->

<!-- JavaScript for Real-Time Filter Update (AJAX) -->
<script>
    document.getElementById("filterForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new URLSearchParams(new FormData(this)).toString();
        fetch("?" + formData, {
            method: "GET",
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector(".product-grid").innerHTML = new DOMParser().parseFromString(html, "text/html").querySelector(".product-grid").innerHTML;
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="js/scripts.js"></script>
</body>
</html>
