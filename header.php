<?php
$pageTitles = [
    'dashboard.php' => 'داشبورد ',
    'products.php' => 'مدیریت محصولات',
    'factor.php' => 'فاکتور ها',
    'pay.php' => ' پرداخت‌ها'
];

$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitle = $pageTitles[$currentPage] ?? 'داشبورد مدیریت';
?>



<header class=" bg-white shadow-sm p-4 flex items-center justify-between">
    <div class="flex items-center">
        <button class="md:hidden text-gray-500 mr-2">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h2 class="text-lg font-semibold text-gray-800"><?php echo $pageTitle; ?></h2>
        </div>
    <div class="flex items-center space-x-4">
                                                                 <div id="date-container">در حال بارگذاری تاریخ...</div>
        <button class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-bell text-xl"></i>
        </button>
    </div>
</header>












