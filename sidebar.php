<style>
/* ===== Sidebar container ===== */
.sidebar {
    width: 256px;
    background-color: #ffffff;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Header */
.sidebar-header {
    padding: 16px;
    border-bottom: 1px solid #e5e7eb;
    text-align: center;
    font-weight: bold;
    font-size: 1.25rem;
    color: #1f2937;
}

/* Menu nav */
.sidebar-nav {
    flex: 1;
    padding: 16px;
    display: flex;
    flex-direction:column;
    gap: 5px;

}

/* Menu items */
.sidebar-item {
    display: flex;
    align-items: center;
    justify-content: start; /* متن و آیکون از سمت راست چسبیده باشه */
    width: 100%; /* پر کردن عرض */
    height: 48px; /* همه لینک‌ها ارتفاع یکسان */
    padding: 0 12px;
    border-radius: 8px;
    text-decoration: none;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.2s ease;
    box-sizing: border-box;
    text-align: right;
    gap: 10px;

}
.sidebar-item span {
    flex: 1; /* باعث میشه متن فضا بگیره و یکنواخت باشه */
}



.sidebar-item:hover {
    color: #2563eb;
    background-color: #eff6ff;
}

/* Active menu */
.sidebar-item.active {
    color: #2563eb;
    background-color: #eff6ff;
}

/* Icon */
.sidebar-item svg {
    margin-left: 8px;
    width: 20px;
    height: 20px;
}

/* Submenu */
.submenu {
    margin-left: 24px;
    margin-top: 4px;
    display: none;
    flex-direction: column;
    gap: 4px;
    margin: 10px;
    border-bottom:1px solid gray;
    border-top:1px solid gray;
}

.submenu a {
    padding: 8px;
    border-radius: 6px;
    color: #4b5563;
    text-decoration: none;
    display: block;
}

.submenu a:hover {
    color: #2563eb;
    background-color: #f0f9ff;
}

/* User box */
.sidebar-user {
    padding: 16px;
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
}
.user-info p {
    margin: 0;
}

.user-info .name {
    font-weight: 500;
    color: #1f2937;
}

.user-info .email {
    font-size: 0.875rem;
    color: #6b7280;
}
</style>

<div class="sidebar">
    <div class="sidebar-header">فروشگاه هادی</div>
    <nav class="sidebar-nav">
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);

    $menu_items = [
        ['url'=>'dashboard.php','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v6h8V3h-8zM3 21h8v-6H3v6z"/></svg>','text'=>'داشبورد'],
        ['url'=>'#','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 16V8a2 2 0 0 0-1-1.73L12 2 4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73L12 22l8-4.27A2 2 0 0 0 21 16z"/></svg>','text'=>'محصولات'],
        ['url'=>'factor.php','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16v16H4zM9 9h6M9 13h4"/></svg>','text'=>'فاکتورها'],
        ['url'=>'pay.php','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M21 10V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4m18 0H3"/></svg>','text'=>'پرداخت ها'],
        ['url'=>'#','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M10.325 4.317 9.257 6.13a1 1 0 0 0 .217 1.317L11 9l-1 2 2 1-1 2 2 1-1 2 1.325 1.683a1 1 0 0 0 1.317.217l1.813-1.068A9 9 0 1 0 10.325 4.317z"/></svg>','text'=>'تنظیمات']
    ];

    foreach($menu_items as $item){
        $active_class = ($current_page === $item['url']) ? 'active' : '';
        if($item['text'] === 'محصولات'){
            echo '<div class="relative">';
            echo '<button class="sidebar-item '.$active_class.'" onclick="toggleSubMenu()">'.$item['icon'].'<span>'.$item['text'].'</span></button>';
            echo '<div class="submenu" id="productsSubMenu">';
            echo '<a href="products.php">لیست محصولات</a>';
            echo '<a href="products-insert-form.php">افزودن محصول</a>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<a href="'.$item['url'].'" class="sidebar-item '.$active_class.'">'.$item['icon'].'<span>'.$item['text'].'</span></a>';
        }
    }
    ?>
    </nav>
    <div class="sidebar-user">
        <div class="user-avatar">U</div>
        <div class="user-info">
            <p class="name">مدیر سیستم</p>
            <p class="email">moazzenhadi46@gmail.com</p>
        </div>
    </div>
</div>

<script>
function toggleSubMenu() {
    var menu = document.getElementById('productsSubMenu');
    var arrow = document.getElementById('arrow');
    if(menu.style.display === 'flex'){
        menu.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    } else {
        menu.style.display = 'flex';
        arrow.style.transform = 'rotate(180deg)';
    }
}
</script>
