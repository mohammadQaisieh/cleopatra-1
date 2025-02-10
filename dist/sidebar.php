<?php
// Example: Assuming the user's Role is stored in a session or fetched from a database.
// session_start();
$userRole = isset($_SESSION['Role']) ? $_SESSION['Role'] : 'Guest'; // Default role is Guest
// print_r($userRole);

?>

<!-- start sidebar -->
<div id="sideBar" class="relative flex flex-col flex-wrap bg-white border-r border-gray-300 p-6 flex-none w-64 md:-ml-64 md:fixed md:top-0 md:z-30 md:h-screen md:shadow-xl animated faster">

    <!-- sidebar content -->
    <div class="flex flex-col">

        <!-- sidebar toggle -->
        <div class="text-right hidden md:block mb-4">
            <button id="sideBarHideBtn">
                <i class="fad fa-times-circle"></i>
            </button>
        </div>
        <!-- end sidebar toggle -->

        <?php if ($userRole == 'Admin'): ?>
            <p class="uppercase text-xs text-gray-600 mb-4 tracking-wider">homes</p>

            <!-- link -->
            <a href="./index.php" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-chart-pie text-xs mr-2"></i>                
                Analytics dashboard
            </a>
            <!-- end link -->

            <!-- link -->
            <a href="./index-1.php" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-shopping-cart text-xs mr-2"></i>
                ecommerce dashboard
            </a>
            <!-- end link -->

            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">apps</p>

            <!-- link -->
            <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-comments text-xs mr-2"></i>
                chat
            </a>
            <!-- end link -->

            <!-- link -->
            <a href="transaction_table.php" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-shield-check text-xs mr-2"></i>
                Transactions Table
            </a>
            <!-- end link -->

            <!-- link -->
            <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-calendar-edit text-xs mr-2"></i>
                calendar
            </a>
            <!-- end link -->

            <!-- link -->
            <a href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-file-invoice-dollar text-xs mr-2"></i>
                invoice
            </a>
            <!-- end link -->

            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">UI Elements</p>

            <!-- link -->
            <a href="./typography.php" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-text text-xs mr-2"></i>
                typography
            </a>
            <!-- end link -->

            <!-- link -->
            <a href="./alert.php" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-whistle text-xs mr-2"></i>
                alerts
            </a>
            <!-- end link -->
        <?php endif; ?>

        <?php if ($userRole == 'Guest'): ?>
            <p class="uppercase text-xs text-gray-600 mb-4 tracking-wider">apps</p>

            <!-- link -->
            <a href="./products.php" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-shopping-cart text-xs mr-2"></i>
                products
            </a>
            <!-- end link -->

            <!-- link -->
            <a href="./email.php" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-envelope-open-text text-xs mr-2"></i>
                email
            </a>
            <!-- end link -->
        <?php endif; ?>

    </div>
    <!-- end sidebar content -->

</div>
<!-- end sidebar -->
