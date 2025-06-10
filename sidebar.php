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
                'icon' => 'fa-chart-line',
                'text' => 'داشبورد'
            ],
            [
                'url' => 'products.php',
                'icon' => 'fa-box',
                'text' => 'محصولات'
            ],
            [
                'url' => 'factor.php',
                'icon' => 'fa-file-invoice-dollar',
                'text' => 'فاکتورها'
            ],
            [
                'url' => 'pay.php',
                'icon' => 'fa-users',
                'text' => 'پرداخت ها'
            ],
            [
                'url' => '#',
                'icon' => 'fa-cog',
                'text' => 'تنظیمات'
            ]
        ];
        
        // نمایش منوها
        foreach ($menu_items as $item) {
            $is_active = ($current_page === $item['url']);
            $active_class = $is_active ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600';
            
            echo '<a href="'.$item['url'].'" class="sidebar-item flex items-center p-3 rounded-lg '.$active_class.'">';
            echo '<i class="fas '.$item['icon'].' ml-2"></i>';
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
                <p class="text-sm text-gray-500">admin@salam.com</p>
            </div>
        </div>
    </div>
</div>