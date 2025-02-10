<!Doctype html>
<html lang="en">
<?php 
   include("./config.php");
   session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
  <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon">  
  <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">  
  <title>Welcome To Cleopatra</title>
  
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


  <!-- strat content -->
  <div class="bg-gray-100 flex-1 p-6 md:mt-16"> 

    <!-- Transactions Table -->
<div class="card mt-6">
    <div class="card-header">All Transactions</div>

    <table class="table-auto w-full text-left">
        <thead>
            <tr>
                <th class="px-4 py-2 border-r">Transaction ID</th>
                <th class="px-4 py-2 border-r">User ID</th>
                <th class="px-4 py-2 border-r">Total Amount (USD)</th>
                <th class="px-4 py-2 border-r">Transaction Date</th>
                <th class="px-4 py-2 border-r">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            <?php
            // Fetch transactions from the database
            connect(); // Call the function to connect to the database
            $query = "SELECT * FROM transactions ORDER BY transaction_date DESC"; // Fetch all transactions
            $results = query($query);

            // Check if transactions exist
            if (mysqli_num_rows($results) > 0) {
                // Loop through each transaction
                while ($row = mysqli_fetch_assoc($results)) {
                    $transaction_id = htmlspecialchars($row['transaction_id']);
                    echo "<tr>";
                    echo "<td class='border border-l-0 px-4 py-2'>" . $transaction_id . "</td>";
                    echo "<td class='border border-l-0 px-4 py-2'>" . htmlspecialchars($row['user_id']) . "</td>";
                    echo "<td class='border border-l-0 px-4 py-2'>$" . number_format($row['total_amount'], 2) . "</td>";
                    echo "<td class='border border-l-0 px-4 py-2'>" . htmlspecialchars($row['transaction_date']) . "</td>";
                    echo "<td class='border border-l-0 border-r-0 px-4 py-2'>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td class='border border-l-0 border-r-0 px-4 py-2'>
                            <button class='btn btn-primary view-details' data-transaction-id='$transaction_id'>
                                View Details
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                // Display a message if no transactions are found
                echo "<tr>";
                echo "<td colspan='6' class='border border-l-0 px-4 py-2 text-center'>No transactions found</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal for Transaction Details -->
<div id="transactionDetailsModal" class="hidden fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-1/2">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold">Transaction Details</h2>
            <button id="closeModal" class="text-red-500">&times;</button>
        </div>
        <div id="modalContent" class="mt-4">
            <!-- Transaction details will be loaded here dynamically -->
        </div>
    </div>
</div>

  </div>
  <!-- end content -->

</div>
<!-- end wrapper -->

<!-- script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="js/scripts.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("transactionDetailsModal");
        const closeModal = document.getElementById("closeModal");
        const modalContent = document.getElementById("modalContent");

        // Add event listener to all view details buttons
        document.querySelectorAll(".view-details").forEach(button => {
            button.addEventListener("click", function () {
                const transactionId = this.getAttribute("data-transaction-id");

                // Fetch transaction details via AJAX
                fetch(`transaction_details.php?transaction_id=${transactionId}`)
                    .then(response => response.text())
                    .then(data => {
                        modalContent.innerHTML = data;
                        modal.classList.remove("hidden");
                    })
                    .catch(error => console.error("Error fetching transaction details:", error));
            });
        });

        // Close modal
        closeModal.addEventListener("click", function () {
            modal.classList.add("hidden");
        });

        // Close modal on clicking outside the modal
        window.addEventListener("click", function (e) {
            if (e.target === modal) {
                modal.classList.add("hidden");
            }
        });
    });
</script>

<!-- end script -->

</body>
</html>

