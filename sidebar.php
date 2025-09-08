<?php
// Sidebar: اصلاح‌شده و پایدار
$current_page = basename($_SERVER['PHP_SELF']); // مثلاً "dashboard.php"

// ساختار منوی اصلی — اگر خواستی، راحت می‌تونی لینک/آیکون/متن اضافه کنی
$menu_items = [
    [
        'url' => 'dashboard.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="20" height="14" x="2" y="3" rx="2" ry="2"/><path d="M8 21h8m-4-4v4"/></g></svg>',
        'text' => 'داشبورد'
    ],
    [
        'url' => null, // والدِ زیرمنو — خودِ والد لینک مستقیمی ندارد
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="M12 8v8m-4-4h8"/></g></svg>',
        'text' => 'محصولات',
        'submenu' => [
            ['url' => 'products.php', 'text' => 'لیست محصولات', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#2563eb"><path d="M3 11h18v2H3z"/></svg>'],
            ['url' => 'products-insert-form.php', 'text' => 'افزودن محصول', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#2563eb"><path d="M11 5h2v14h-2zM5 11h14v2H5z"/></svg>'],
        ]
    ],
    [
        'url' => null,
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6m-4 5H8m8 4H8m2-8H8"/></g></svg>',
        'text' => 'فاکتورها',
        'submenu' => [
            ['url' => 'factor-macker.html', 'text' => 'فاکتور ساز', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#2563eb"><path d="M3 3h18v4H3zM6 10h12v11H6z"/></svg>'],
            ['url' => 'factor.php', 'text' => 'فاکتور ماهانه', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#2563eb"><path d="M3 3h18v4H3zM3 8h18v13H3z"/></svg>'],
        ]
    ],
    [
        'url' => 'pay.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="22" height="16" x="1" y="4" rx="2" ry="2"/><path d="M1 10h22"/></g></svg>',
        'text' => 'پرداخت‌ها'
    ],
    [
        'url' => 'Statistics.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><path fill="currentColor" d="M3 17h2v-7H3v7zm4 0h2V7H7v10zm4 0h2v-4h-2v4zm4 0h2V4h-2v13zm4 0h2V9h-2v8z"/></svg>',
        'text' => 'آمار'
    ],
    [
        'url' => 'settings.php',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024" fill="#2563eb"><path fill="currentColor" d="M600.704 64a32 32 0 0 1 30.464 22.208l35.2 109.376c14.784 7.232 28.928 15.36 42.432 24.512l112.384-24.192a32 32 0 0 1 34.432 15.36L944.32 364.8a32 32 0 0 1-4.032 37.504l-77.12 85.12a357.12 357.12 0 0 1 0 49.024l77.12 85.248a32 32 0 0 1 4.032 37.504l-88.704 153.6a32 32 0 0 1-34.432 15.296L708.8 803.904c-13.44 9.088-27.648 17.28-42.368 24.512l-35.264 109.376A32 32 0 0 1 600.704 960H423.296a32 32 0 0 1-30.464-22.208L357.696 828.48a351.616 351.616 0 0 1-42.56-24.64l-112.32 24.256a32 32 0 0 1-34.432-15.36L79.68 659.2a32 32 0 0 1 4.032-37.504l77.12-85.248a357.12 357.12 0 0 1 0-48.896l-77.12-85.248A32 32 0 0 1 79.68 364.8l88.704-153.6a32 32 0 0 1 34.432-15.296l112.32 24.256c13.568-9.152 27.776-17.408 42.56-24.64l35.2-109.312A32 32 0 0 1 423.232 64H600.64z"/></svg>',
        'text' => 'تنظیمات'
    ]
];

// CSS + HTML + PHP output
?>
<style>
/* ===== Sidebar container ===== */

*{
    font-family: peyda;

}
.sidebar {
    width: 256px;
    background-color: #ffffff;
    box-shadow: 0 4px 6px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 10px;
    direction: rtl; /* راست‌چین برای زبان فارسی */
}

/* Header */
.sidebar-header {
    padding: 16px;
    border-bottom: 1px solid #e5e7eb;
    text-align: center;
    font-weight: 700;
    font-size: 1.05rem;
    color: #1f2937;
}

/* Menu nav */
.sidebar-nav {
    flex: 1;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

/* Menu items */
.sidebar-item {
    display: flex;
    align-items: center;
    width: 100%;
    height: 48px;
    padding: 0 12px;
    border-radius: 8px;
    text-decoration: none;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.15s ease;
    box-sizing: border-box;
    gap: 10px;
    text-align: right;
    background: transparent;
    border: none;
    outline: none;
    font-size: 0.95rem;
}

/* make the text take available space */
.sidebar-item span {
    flex: 1;
    text-align: right;
}

/* push icon to the left (RTL-aware) */
.sidebar-item svg {
    width: 20px;
    height: 20px;
    margin-inline-start: 8px; /* logical property */
    margin-inline-end: 0;
    flex-shrink: 0;
}

/* hover + active */
.sidebar-item:hover {
    color: #2563eb;
    background-color: #eff6ff;
}
.sidebar-item.active {
    color: #2563eb;
    background-color: #eff6ff;
    font-weight: 600;
}

/* Submenu */
.submenu {
    display: none;
    flex-direction: column;
    gap: 4px;
    margin-right: 12px; /* indent (RTL) */
    margin-left: 8px;
    padding: 8px 0;
    border-top: 1px solid transparent;
    border-bottom: 1px solid transparent;
}
.submenu.open {
    display: flex;
}

/* submenu links */
.submenu a {
    padding: 8px 10px;
    border-radius: 6px;
    color: #4b5563;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: flex-start;
    font-size: 0.92rem;
}
.submenu a .icon {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}
.submenu a:hover {
    color: #2563eb;
    background-color: #f0f9ff;
}
.submenu a.active-sub {
    color: #2563eb;
    background-color: #eef6ff;
    font-weight: 600;
}

/* User box */
.sidebar-user {
    padding: 12px 16px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 12px;
}
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #bfdbfe;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2563eb;
    font-size: 18px;
    flex-shrink: 0;
}
.user-info p {
    margin: 0;
    line-height: 1;
}
.user-info .name {
    font-weight: 500;
    color: #1f2937;
}
.user-info .email {
    font-size: 0.82rem;
    color: #6b7280;
    direction: ltr; /* ایمیل را LTR نشان بده */
    text-align: left;
}
</style>

<div class="sidebar" role="navigation" aria-label="منوی کناری">
    <div class="sidebar-header">فروشگاه هادی</div>

    <nav class="sidebar-nav">
        <?php
        foreach ($menu_items as $index => $item) {
            // تشخیص فعال بودن (برای آیتم‌های ساده و والدهایی که زیرمنو دارند)
            $is_active = false;
            $has_sub = isset($item['submenu']) && is_array($item['submenu']) && count($item['submenu']) > 0;

            if ($has_sub) {
                // بررسی زیرمنوها برای تعیین active بودن والد
                foreach ($item['submenu'] as $sub) {
                    if (basename($sub['url']) === $current_page) {
                        $is_active = true;
                        break;
                    }
                }
            } else {
                if ($item['url'] !== null && basename($item['url']) === $current_page) {
                    $is_active = true;
                }
            }

            // شناسهٔ یکتا برای زیرمنو (اگر وجود دارد)
            $submenuId = 'submenu_' . $index;

            if ($has_sub) {
                // دکمهٔ بازشونده برای والد
                echo '<div class="relative">';
                echo '<button class="sidebar-item ' . ($is_active ? 'active' : '') . '" aria-expanded="' . ($is_active ? 'true' : 'false') . '" data-toggle="' . $submenuId . '" onclick="toggleSubMenu(\'' . $submenuId . '\', this)">';
                echo $item['icon'];
                echo '<span>' . htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') . '</span>';
                echo '</button>';

                // زیرمنو — در صورتی که والد فعاله، کلاس open را اضافه کن
                echo '<div class="submenu ' . ($is_active ? 'open' : '') . '" id="' . $submenuId . '">';

                foreach ($item['submenu'] as $sub) {
                    $sub_active = (basename($sub['url']) === $current_page) ? 'active-sub' : '';
                    $sub_icon_html = isset($sub['icon']) ? $sub['icon'] : '';
                    echo '<a href="' . htmlspecialchars($sub['url'], ENT_QUOTES, 'UTF-8') . '" class="' . $sub_active . '">';
                    echo '<span class="icon">' . $sub_icon_html . '</span>';
                    echo '<span>' . htmlspecialchars($sub['text'], ENT_QUOTES, 'UTF-8') . '</span>';
                    echo '</a>';
                }

                echo '</div>'; // .submenu
                echo '</div>'; // .relative
            } else {
                // آیتم بدون زیرمنو
                $href = $item['url'] ? htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') : '#';
                echo '<a href="' . $href . '" class="sidebar-item ' . ($is_active ? 'active' : '') . '">';
                echo $item['icon'];
                echo '<span>' . htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') . '</span>';
                echo '</a>';
            }
        }
        ?>
    </nav>

    <div class="sidebar-user">
        <div class="user-avatar">H</div>
        <div class="user-info">
            <p class="name">پشتیبانی</p>
            <p class="email">moazzenhadi46@gmail.com</p>
        </div>
    </div>
</div>

<script>
// مدیریت باز/بسته شدن زیرمنوها به‌صورت امن و accessible
function toggleSubMenu(id, btn) {
    var menu = document.getElementById(id);
    if (!menu) return;

    var isOpen = menu.classList.contains('open');

    // اول بقیه زیرمنوهای باز را ببند
    document.querySelectorAll('.submenu.open').forEach(function(other){
        if (other.id !== id) {
            other.classList.remove('open');
            // دکمهٔ مرتبط را هم aria-expanded false کن
            var toggleBtn = document.querySelector('[data-toggle="' + other.id + '"]');
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
        }
    });

    if (isOpen) {
        menu.classList.remove('open');
        if (btn) btn.setAttribute('aria-expanded', 'false');
    } else {
        menu.classList.add('open');
        if (btn) btn.setAttribute('aria-expanded', 'true');
        // اسکرول به منوی باز شده (اختیاری)
        menu.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// اگر خواستی می‌توانی این اسکریپت را طوری تنظیم کنی که
// زیرمنوها بر اساس مسیر فعلی هنگام لود صفحه باز بمانند — ما این رفتار را با کلاس 'open' در PHP انجام دادیم.
</script>
