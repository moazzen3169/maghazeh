<!-- Sidebar -->
<div class="sidebar w-64 bg-white shadow-lg hidden md:flex flex-col">
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-800 text-center">فروشگاه هادی</h1>
    </div>
    <nav class="flex-1 p-4 space-y-2">
    <?php
// تشخیص صفحه فعلی
$current_page = basename($_SERVER['PHP_SELF']);

// لیست لینک‌های منو
$menu_items = [
    [
        'url' => 'dashboard.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v6h8V3h-8zM3 21h8v-6H3v6z"/></svg>',
        'text' => 'داشبورد'
    ],
    [
        'url' => 'products.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M21 16V8a2 2 0 0 0-1-1.73L12 2 4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73L12 22l8-4.27A2 2 0 0 0 21 16z"/></svg>',
        'text' => 'محصولات'
    ],
    [
        'url' => 'factor.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 4h16v16H4zM9 9h6M9 13h4"/></svg>',
        'text' => 'فاکتورها'
    ],
    [
        'url' => 'pay.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M21 10V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4m18 0H3"/></svg>',
        'text' => 'پرداخت ها'
    ],
    [
        'url' => '#',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M10.325 4.317 9.257 6.13a1 1 0 0 0 .217 1.317L11 9l-1 2 2 1-1 2 2 1-1 2 1.325 1.683a1 1 0 0 0 1.317.217l1.813-1.068A9 9 0 1 0 10.325 4.317z"/></svg>',
        'text' => 'تنظیمات'
    ]
];

// نمایش منوها
foreach ($menu_items as $item) {
    $is_active = ($current_page === $item['url']);
    $active_class = $is_active ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600';
    
    echo '<a href="'.$item['url'].'" class="sidebar-item flex items-center p-3 rounded-lg '.$active_class.'">';
    echo $item['icon'];
    echo '<span>'.$item['text'].'</span>';
    echo '</a>';
}
?>

    </nav>
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-user text-blue-500"></i>
            </div>
            <div class="mr-3">
                <p class="font-medium text-gray-800">مدیر سیستم</p>
                <p class="text-sm text-gray-500">moazzenhadi46@gmail.com</p>
            </div>
        </div>
    </div>
</div>