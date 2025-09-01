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

<header class="bg-white shadow-sm p-4 flex items-center justify-between">
    <div class="flex items-center">
        <!-- آیکون منو (bars) -->
        <button class="md:hidden text-gray-500 mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h2 class="text-lg font-semibold text-gray-800"><?php echo $pageTitle; ?></h2>
    </div>

    <div class="flex items-center space-x-4">
        <div id="date-container">در حال بارگذاری تاریخ...</div>

        <!-- آیکون نوتیفیکیشن (bell) -->
        <button class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </button>
    </div>
</header>
