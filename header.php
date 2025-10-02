<?php
$pageTitles = [
    'dashboard.php' => 'داشبورد ',
    'products.php' => 'مدیریت محصولات',
    'factor.php' => 'فاکتور ها',
    'pay.php' => ' پرداخت‌ها',
    'count_all_products.php' => 'مدیریت محصولات موجود'
];

$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitle = $pageTitles[$currentPage] ?? 'داشبورد مدیریت';
?>

<header class="app-header">
    <div class="flex items-center gap-3">
        <button class="header-action md:hidden" aria-label="باز کردن منو">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" x2="20" y1="6" y2="6" />
                <line x1="4" x2="20" y1="12" y2="12" />
                <line x1="4" x2="14" y1="18" y2="18" />
            </svg>
        </button>
        <h2><?php echo $pageTitle; ?></h2>
    </div>

    <div class="header-meta">
        <div id="date-container">در حال بارگذاری تاریخ...</div>
        <button class="header-action" aria-label="اعلان‌ها">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
            </svg>
        </button>
    </div>
    <script src="scripts.js"></script>
</header>



