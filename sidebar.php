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



.submenu a{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #4b5563; /* رنگ پیش‌فرض خاکستری */
  text-decoration: none;
  transition: color 0.3s ease;
}

.menu-item:hover {
  color: #2563eb; /* آبی هنگام هاور */
}






</style>

<div class="sidebar">
    <div class="sidebar-header">فروشگاه هادی</div>
    <nav class="sidebar-nav">
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);

    $menu_items = [
        ['url'=>'dashboard.php','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="20" height="14" x="2" y="3" rx="2" ry="2"/><path d="M8 21h8m-4-4v4"/></g></svg>','text'=>'داشبورد'],
        ['url'=>'#','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="M12 8v8m-4-4h8"/></g></svg>','text'=>'محصولات'],
        ['url'=>'factor.php','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6m-4 5H8m8 4H8m2-8H8"/></g></svg>','text'=>'فاکتورها'],
        ['url'=>'pay.php','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="22" height="16" x="1" y="4" rx="2" ry="2"/><path d="M1 10h22"/></g></svg>','text'=>'پرداخت ها'],
        ['url'=>'#','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 1024 1024" fill="#2563eb"><path fill="currentColor" d="M600.704 64a32 32 0 0 1 30.464 22.208l35.2 109.376c14.784 7.232 28.928 15.36 42.432 24.512l112.384-24.192a32 32 0 0 1 34.432 15.36L944.32 364.8a32 32 0 0 1-4.032 37.504l-77.12 85.12a357.12 357.12 0 0 1 0 49.024l77.12 85.248a32 32 0 0 1 4.032 37.504l-88.704 153.6a32 32 0 0 1-34.432 15.296L708.8 803.904c-13.44 9.088-27.648 17.28-42.368 24.512l-35.264 109.376A32 32 0 0 1 600.704 960H423.296a32 32 0 0 1-30.464-22.208L357.696 828.48a351.616 351.616 0 0 1-42.56-24.64l-112.32 24.256a32 32 0 0 1-34.432-15.36L79.68 659.2a32 32 0 0 1 4.032-37.504l77.12-85.248a357.12 357.12 0 0 1 0-48.896l-77.12-85.248A32 32 0 0 1 79.68 364.8l88.704-153.6a32 32 0 0 1 34.432-15.296l112.32 24.256c13.568-9.152 27.776-17.408 42.56-24.64l35.2-109.312A32 32 0 0 1 423.232 64H600.64zm-23.424 64H446.72l-36.352 113.088l-24.512 11.968a294.113 294.113 0 0 0-34.816 20.096l-22.656 15.36l-116.224-25.088l-65.28 113.152l79.68 88.192l-1.92 27.136a293.12 293.12 0 0 0 0 40.192l1.92 27.136l-79.808 88.192l65.344 113.152l116.224-25.024l22.656 15.296a294.113 294.113 0 0 0 34.816 20.096l24.512 11.968L446.72 896h130.688l36.48-113.152l24.448-11.904a288.282 288.282 0 0 0 34.752-20.096l22.592-15.296l116.288 25.024l65.28-113.152l-79.744-88.192l1.92-27.136a293.12 293.12 0 0 0 0-40.256l-1.92-27.136l79.808-88.128l-65.344-113.152l-116.288 24.96l-22.592-15.232a287.616 287.616 0 0 0-34.752-20.096l-24.448-11.904L577.344 128zM512 320a192 192 0 1 1 0 384a192 192 0 0 1 0-384zm0 64a128 128 0 1 0 0 256a128 128 0 0 0 0-256z"/></svg>','text'=>'تنظیمات']
    ];

    foreach($menu_items as $item){
        $active_class = ($current_page === $item['url']) ? 'active' : '';
        if($item['text'] === 'محصولات'){
            echo '<div class="relative">';
            echo '<button class="sidebar-item '.$active_class.'" onclick="toggleSubMenu()">'.$item['icon'].'<span>'.$item['text'].'</span></button>';
            echo '<div class="submenu" id="productsSubMenu">';
            echo '<a href="products.php"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="#2563eb" stroke-width="2"><path d="M3 11c0-3.771 0-5.657 1.172-6.828C5.343 3 7.229 3 11 3h2c3.771 0 5.657 0 6.828 1.172C21 5.343 21 7.229 21 11v2c0 3.771 0 5.657-1.172 6.828C18.657 21 16.771 21 13 21h-2c-3.771 0-5.657 0-6.828-1.172C3 18.657 3 16.771 3 13z"/><path stroke-linecap="square" stroke-linejoin="round" d="M12 8v8m4-4H8"/></g></svg> لیست محصولات</a>';
            echo '<a href="products-insert-form.php"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="#2563eb" stroke-width="2"><path d="M3 11c0-3.771 0-5.657 1.172-6.828C5.343 3 7.229 3 11 3h2c3.771 0 5.657 0 6.828 1.172C21 5.343 21 7.229 21 11v2c0 3.771 0 5.657-1.172 6.828C18.657 21 16.771 21 13 21h-2c-3.771 0-5.657 0-6.828-1.172C3 18.657 3 16.771 3 13z"/><path stroke-linecap="square" stroke-linejoin="round" d="M12 8v8m4-4H8"/></g></svg>افزودن محصول</a>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<a href="'.$item['url'].'" class="sidebar-item '.$active_class.'">'.$item['icon'].'<span>'.$item['text'].'</span></a>';
        }
    }
    ?>
    </nav>
    <div class="sidebar-user">
        <div class="user-avatar">H</div>
        <div class="user-info">
            <p class="name">ایمیل پشتیبانی</p>
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
