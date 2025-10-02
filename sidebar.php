<?php
// Sidebar navigation configuration
$current_page = basename($_SERVER['PHP_SELF']);

$menu_items = [
    [
        'url' => 'dashboard.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2" ry="2"/><path d="M8 21h8m-4-4v4"/></svg>',
        'text' => 'داشبورد'
    ],
    [
        'url' => null,
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="M12 8v8m-4-4h8"/></svg>',
        'text' => 'محصولات',
        'submenu' => [
            ['url' => 'products.php', 'text' => 'لیست محصولات', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 11h18v2H3z"/></svg>'],
            ['url' => 'products-insert-form.php', 'text' => 'افزودن محصول', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M11 5h2v14h-2zM5 11h14v2H5z"/></svg>'],
        ]
    ],
    [
        'url' => null,
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6m-4 5H8m8 4H8m2-8H8"/></svg>',
        'text' => 'فاکتورها',
        'submenu' => [
            ['url' => 'factor-macker.html', 'text' => 'فاکتور ساز', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v4H3zM6 10h12v11H6z"/></svg>'],
            ['url' => 'factor.php', 'text' => 'فاکتور ماهانه', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v4H3zM3 8h18v13H3z"/></svg>'],
        ]
    ],
    [
        'url' => 'pay.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="22" height="16" x="1" y="4" rx="2" ry="2"/><path d="M1 10h22"/></svg>',
        'text' => 'پرداخت‌ها'
    ],
    [
        'url' => 'Statistics.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17h2v-7H3v7zm4 0h2V7H7v10zm4 0h2v-4h-2v4zm4 0h2V4h-2v13zm4 0h2V9h-2v8z"/></svg>',
        'text' => 'گزارشات و آمار'
    ],
    [
        'url' => 'count_all_products.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17h2v-7H3v7zm4 0h2V7H7v10zm4 0h2v-4h-2v4zm4 0h2V4h-2v13zm4 0h2V9h-2v8z"/></svg>',
        'text' => 'محصولات موجود'
    ],
];
?>
<aside class="sidebar" role="navigation" aria-label="منوی کناری">
    <div class="sidebar-header">
        <h1>فروشگاه هادی</h1>
        <span>داشبورد مدیریتی</span>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($menu_items as $index => $item): ?>
            <?php
            $is_active = false;
            $has_sub = isset($item['submenu']) && is_array($item['submenu']) && count($item['submenu']) > 0;

            if ($has_sub) {
                foreach ($item['submenu'] as $sub) {
                    if (basename($sub['url']) === $current_page) {
                        $is_active = true;
                        break;
                    }
                }
            } elseif ($item['url'] !== null && basename($item['url']) === $current_page) {
                $is_active = true;
            }

            $submenuId = 'submenu_' . $index;
            ?>

            <?php if ($has_sub): ?>
                <div class="sidebar-group">
                    <button class="sidebar-item <?php echo $is_active ? 'active' : ''; ?>" aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>" data-toggle="<?php echo $submenuId; ?>" onclick="toggleSubMenu('<?php echo $submenuId; ?>', this)">
                        <?php echo $item['icon']; ?>
                        <span><?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="chevron">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>
                    <div class="submenu <?php echo $is_active ? 'open' : ''; ?>" id="<?php echo $submenuId; ?>">
                        <?php foreach ($item['submenu'] as $sub): ?>
                            <?php $sub_active = (basename($sub['url']) === $current_page) ? 'active-sub' : ''; ?>
                            <a href="<?php echo htmlspecialchars($sub['url'], ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $sub_active; ?>">
                                <?php if (isset($sub['icon'])): ?>
                                    <span class="icon"><?php echo $sub['icon']; ?></span>
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($sub['text'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>" class="sidebar-item <?php echo $is_active ? 'active' : ''; ?>">
                    <?php echo $item['icon']; ?>
                    <span><?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-user">
        <div class="user-avatar">H</div>
        <div class="user-info">
            <span class="name">پشتیبانی</span>
            <span class="role">moazzenhadi46@gmail.com</span>
        </div>
    </div>
</aside>

<script>
function toggleSubMenu(id, btn) {
    var menu = document.getElementById(id);
    if (!menu) return;

    var isOpen = menu.classList.contains('open');

    document.querySelectorAll('.submenu.open').forEach(function(other) {
        if (other.id !== id) {
            other.classList.remove('open');
            var toggleBtn = document.querySelector('[data-toggle="' + other.id + '"]');
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.classList.remove('open');
            }
        }
    });

    if (isOpen) {
        menu.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
        btn.classList.remove('open');
    } else {
        menu.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
        btn.classList.add('open');
    }
}
</script>
