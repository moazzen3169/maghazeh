<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم مدیریت محصولات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#10b981',
                        dark: '#1e293b',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap');
        * {
            font-family: 'Vazirmatn', sans-serif;
        }
        
        .floating-input {
            @apply block w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent;
        }
        
        .floating-label {
            @apply absolute right-3 top-3 text-gray-400 pointer-events-none transition-all duration-200;
        }
        
        .floating-input:focus + .floating-label,
        .floating-input:not(:placeholder-shown) + .floating-label {
            @apply text-xs -top-2 right-3 bg-white px-1 text-primary;
        }
        
        .product-card {
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- هدر -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
            <h1 class="text-3xl font-bold text-dark flex items-center gap-2">
                <i class="fas fa-boxes text-primary"></i>
                سیستم مدیریت محصولات
            </h1>
            <div class="bg-primary text-white px-4 py-2 rounded-lg shadow-md">
                <span id="current-date" class="font-medium"></span>
                <span id="current-time" class="mr-2"></span>
                <i class="fas fa-clock"></i>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- پنل سمت چپ - ثبت محصول جدید -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-secondary"></i>
                        ثبت محصول جدید
                    </h2>
                    
                    <form id="product-form" class="space-y-4">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="product-name" 
                                class="floating-input" 
                                placeholder=" "
                                required
                            >
                            <label for="product-name" class="floating-label">نام محصول</label>
                        </div>
                        
                        <div class="relative">
                            <input 
                                type="number" 
                                id="product-price" 
                                class="floating-input" 
                                placeholder=" "
                                min="0"
                                required
                            >
                            <label for="product-price" class="floating-label">قیمت (تومان)</label>
                        </div>
                        
                        <div class="relative">
                            <input 
                                type="number" 
                                id="product-quantity" 
                                class="floating-input" 
                                placeholder=" "
                                min="1"
                                required
                            >
                            <label for="product-quantity" class="floating-label">تعداد</label>
                        </div>
                        
                        <div class="relative">
                            <input 
                                type="text" 
                                id="product-category" 
                                class="floating-input" 
                                placeholder=" "
                                required
                            >
                            <label for="product-category" class="floating-label">دسته‌بندی</label>
                        </div>
                        
                        <div class="relative">
                            <textarea 
                                id="product-description" 
                                rows="3"
                                class="floating-input"
                                placeholder=" "
                            ></textarea>
                            <label for="product-description" class="floating-label">توضیحات (اختیاری)</label>
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-save"></i>
                            ثبت محصول
                        </button>
                    </form>
                    
                    <div id="product-code-display" class="mt-6 p-4 bg-gray-100 rounded-lg hidden">
                        <h3 class="font-medium text-gray-700 mb-1">کد محصول تولید شده:</h3>
                        <div class="flex items-center justify-between bg-white p-3 rounded-md border border-gray-300">
                            <span id="generated-code" class="font-bold text-xl text-primary"></span>
                            <button 
                                id="copy-code"
                                class="text-gray-500 hover:text-primary transition"
                                title="کپی کردن کد"
                            >
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">این کد برای رهگیری محصول استفاده می‌شود</p>
                    </div>
                </div>
            </div>
            
            <!-- پنل وسط - لیست محصولات و فروش -->
            <div class="lg:col-span-2 space-y-6">
                <!-- جستجوی محصول -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-search text-blue-500"></i>
                        جستجوی و فروش محصول
                    </h2>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-grow">
                            <input 
                                type="text" 
                                id="search-code" 
                                class="floating-input" 
                                placeholder=" "
                            >
                            <label for="search-code" class="floating-label">کد محصول</label>
                        </div>
                        <button 
                            id="search-btn"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2 whitespace-nowrap"
                        >
                            <i class="fas fa-search"></i>
                            جستجو
                        </button>
                    </div>
                    
                    <div id="search-result" class="mt-4 hidden">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg text-dark" id="found-product-name"></h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-gray-600" id="found-product-category"></span>
                                        <span class="text-primary font-medium" id="found-product-price"></span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2" id="found-product-description"></p>
                                </div>
                                <span class="bg-primary text-white text-xs px-2 py-1 rounded" id="found-product-code"></span>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center gap-4">
                                    <div class="relative flex-grow">
                                        <input 
                                            type="number" 
                                            id="sale-quantity" 
                                            class="floating-input" 
                                            placeholder=" "
                                            min="1"
                                            value="1"
                                        >
                                        <label for="sale-quantity" class="floating-label">تعداد</label>
                                    </div>
                                    <button 
                                        id="sell-btn"
                                        class="bg-secondary hover:bg-green-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2 whitespace-nowrap"
                                        disabled
                                    >
                                        <i class="fas fa-cash-register"></i>
                                        ثبت فروش
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">موجودی: <span id="available-quantity" class="font-medium">0</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- لیست محصولات -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-dark flex items-center gap-2">
                            <i class="fas fa-boxes-stacked text-orange-500"></i>
                            لیست محصولات
                        </h2>
                        <div class="relative">
                            <select id="category-filter" class="bg-gray-50 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="all">همه دسته‌بندی‌ها</option>
                                <!-- دسته‌بندی‌ها به صورت داینامیک اضافه می‌شوند -->
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کد محصول</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نام</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">دسته‌بندی</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قیمت</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">موجودی</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body" class="bg-white divide-y divide-gray-200">
                                <!-- محصولات به صورت داینامیک اضافه می‌شوند -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="products-empty" class="text-center py-8">
                        <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">هنوز محصولی ثبت نشده است</p>
                    </div>
                </div>
                
                <!-- محصولات فروخته شده -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-receipt text-purple-500"></i>
                        محصولات فروخته شده
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کد محصول</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نام</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تعداد</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مبلغ کل</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ فروش</th>
                                </tr>
                            </thead>
                            <tbody id="sold-products-table-body" class="bg-white divide-y divide-gray-200">
                                <!-- محصولات فروخته شده به صورت داینامیک اضافه می‌شوند -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="sold-empty" class="text-center py-8">
                        <i class="fas fa-smile text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">هنوز محصولی فروخته نشده است</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- مودال تأیید -->
    <div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-900">آیا مطمئن هستید؟</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-6">
                <p id="modal-message" class="text-gray-600">با تأیید این عمل، محصول از لیست موجودی حذف می‌شود.</p>
            </div>
            <div class="flex justify-end gap-3">
                <button id="cancel-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    انصراف
                </button>
                <button id="confirm-btn" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    تأیید
                </button>
            </div>
        </div>
    </div>
    
    <!-- ناتفیکیشن -->
    <div id="notification" class="fixed bottom-5 left-5 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 transform translate-y-16 transition-transform duration-300">
        <i class="fas fa-check-circle"></i>
        <span id="notification-message">عملیات با موفقیت انجام شد</span>
    </div>

    <script>
        // داده‌های نمونه
        let products = JSON.parse(localStorage.getItem('products')) || [];
        let soldProducts = JSON.parse(localStorage.getItem('soldProducts')) || [];
        let categories = JSON.parse(localStorage.getItem('categories')) || ['الکترونیکی', 'لباس', 'لوازم خانگی'];
        
        // عناصر DOM
        const productForm = document.getElementById('product-form');
        const searchForm = document.getElementById('search-form');
        const searchBtn = document.getElementById('search-btn');
        const sellBtn = document.getElementById('sell-btn');
        const productsTableBody = document.getElementById('products-table-body');
        const soldProductsTableBody = document.getElementById('sold-products-table-body');
        const searchResult = document.getElementById('search-result');
        const productCodeDisplay = document.getElementById('product-code-display');
        const categoryFilter = document.getElementById('category-filter');
        const productsEmpty = document.getElementById('products-empty');
        const soldEmpty = document.getElementById('sold-empty');
        const confirmModal = document.getElementById('confirm-modal');
        const notification = document.getElementById('notification');
        
        // نمایش تاریخ و ساعت
        function updateDateTime() {
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                weekday: 'long'
            };
            const now = new Date();
            document.getElementById('current-date').textContent = now.toLocaleDateString('fa-IR', options);
            document.getElementById('current-time').textContent = now.toLocaleTimeString('fa-IR');
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
        
        // تولید کد منحصر به فرد
        function generateProductCode() {
            const timestamp = Date.now().toString().slice(-5);
            const random = Math.floor(1000 + Math.random() * 9000);
            return `PR-${timestamp}${random}`;
        }
        
        // ذخیره داده‌ها در localStorage
        function saveData() {
            localStorage.setItem('products', JSON.stringify(products));
            localStorage.setItem('soldProducts', JSON.stringify(soldProducts));
            localStorage.setItem('categories', JSON.stringify(categories));
        }
        
        // نمایش ناتفیکیشن
        function showNotification(message, isSuccess = true) {
            notification.style.backgroundColor = isSuccess ? '#10b981' : '#ef4444';
            notification.querySelector('i').className = isSuccess ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            document.getElementById('notification-message').textContent = message;
            notification.classList.remove('translate-y-16');
            
            setTimeout(() => {
                notification.classList.add('translate-y-16');
            }, 3000);
        }
        
        // آپدیت فیلتر دسته‌بندی‌ها
        function updateCategoryFilter() {
            categoryFilter.innerHTML = '<option value="all">همه دسته‌بندی‌ها</option>';
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categoryFilter.appendChild(option);
            });
        }
        
        // رندر لیست محصولات
        function renderProducts(filter = 'all') {
            productsTableBody.innerHTML = '';
            
            const filteredProducts = filter === 'all' 
                ? products 
                : products.filter(p => p.category === filter);
                
            if (filteredProducts.length === 0) {
                productsEmpty.classList.remove('hidden');
            } else {
                productsEmpty.classList.add('hidden');
                filteredProducts.forEach(product => {
                    const tr = document.createElement('tr');
                    tr.className = 'product-card hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${product.code}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${product.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${product.category}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${product.price.toLocaleString('fa-IR')} تومان</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${product.quantity}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex gap-2">
                            <button class="text-blue-500 hover:text-blue-700" onclick="prepareForSale('${product.code}')" title="فروش">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteProduct('${product.code}')" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    productsTableBody.appendChild(tr);
                });
            }
        }
        
        // رندر لیست محصولات فروخته شده
        function renderSoldProducts() {
            soldProductsTableBody.innerHTML = '';
            
            if (soldProducts.length === 0) {
                soldEmpty.classList.remove('hidden');
            } else {
                soldEmpty.classList.add('hidden');
                soldProducts.forEach(sale => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${sale.productCode}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${sale.productName}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${sale.quantity}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${sale.totalPrice.toLocaleString('fa-IR')} تومان</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(sale.saleDate).toLocaleDateString('fa-IR')}</td>
                    `;
                    soldProductsTableBody.appendChild(tr);
                });
            }
        }
        
        // آماده‌سازی محصول برای فروش از طریق جستجو
        window.prepareForSale = function(productCode) {
            document.getElementById('search-code').value = productCode;
            searchProduct();
        }
        
        // جستجوی محصول براساس کد
        function searchProduct() {
            const code = document.getElementById('search-code').value.trim();
            if (!code) {
                showNotification('لطفاً کد محصول را وارد کنید', false);
                return;
            }
            
            const product = products.find(p => p.code === code);
            if (!product) {
                showNotification('محصولی با این کد یافت نشد', false);
                searchResult.classList.add('hidden');
                return;
            }
            
            document.getElementById('found-product-name').textContent = product.name;
            document.getElementById('found-product-category').textContent = product.category;
            document.getElementById('found-product-price').textContent = `${product.price.toLocaleString('fa-IR')} تومان`;
            document.getElementById('found-product-description').textContent = product.description || 'توضیحات ندارد';
            document.getElementById('found-product-code').textContent = product.code;
            document.getElementById('available-quantity').textContent = product.quantity;
            document.getElementById('sale-quantity').max = product.quantity;
            document.getElementById('sale-quantity').value = 1;
            document.getElementById('sell-btn').disabled = false;
            
            searchResult.classList.remove('hidden');
        }
        
        // ثبت فروش محصول
        function sellProduct() {
            const code = document.getElementById('search-code').value.trim();
            const quantity = parseInt(document.getElementById('sale-quantity').value);
            
            const productIndex = products.findIndex(p => p.code === code);
            if (productIndex === -1) {
                showNotification('محصول یافت نشد!', false);
                return;
            }
            
            const product = products[productIndex];
            if (quantity > product.quantity) {
                showNotification('تعداد درخواستی بیشتر از موجودی است', false);
                return;
            }
            
            // کاهش موجودی
            product.quantity -= quantity;
            if (product.quantity === 0) {
                products.splice(productIndex, 1);
            }
            
            // افزودن به لیست فروخته شده‌ها
            soldProducts.push({
                productCode: product.code,
                productName: product.name,
                quantity: quantity,
                totalPrice: product.price * quantity,
                saleDate: new Date().toISOString()
            });
            
            saveData();
            renderProducts(categoryFilter.value);
            renderSoldProducts();
            searchResult.classList.add('hidden');
            document.getElementById('search-code').value = '';
            
            showNotification(`محصول ${product.name} با موفقیت فروخته شد`);
            
            // بررسی و اضافه کردن دسته‌بندی جدید
            if (!categories.includes(product.category)) {
                categories.push(product.category);
                updateCategoryFilter();
            }
        }
        
        // حذف محصول
        window.deleteProduct = function(code) {
            const product = products.find(p => p.code === code);
            if (!product) return;
            
            openModal(
                `آیا از حذف محصول ${product.name} مطمئن هستید؟`,
                () => {
                    products = products.filter(p => p.code !== code);
                    saveData();
                    renderProducts(categoryFilter.value);
                    showNotification('محصول با موفقیت حذف شد');
                }
            );
        }
        
        // مدیریت مودال تأیید
        function openModal(message, confirmCallback) {
            document.getElementById('modal-message').textContent = message;
            confirmModal.classList.remove('hidden');
            
            const modalContent = confirmModal.querySelector('div > div');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            document.getElementById('confirm-btn').onclick = function() {
                confirmCallback();
                closeModal();
            };
        }
        
        function closeModal() {
            const modalContent = confirmModal.querySelector('div > div');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                confirmModal.classList.add('hidden');
            }, 300);
        }
        
        // رویدادهای صفحه
        document.addEventListener('DOMContentLoaded', function() {
            // رندری اولیه
            updateCategoryFilter();
            renderProducts();
            renderSoldProducts();
            
            // کپی کردن کد محصول
            document.getElementById('copy-code').addEventListener('click', function() {
                const code = document.getElementById('generated-code').textContent;
                navigator.clipboard.writeText(code).then(() => {
                    showNotification('کد با موفقیت کپی شد');
                });
            });
            
            // فرم ثبت محصول
            productForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const name = document.getElementById('product-name').value;
                const price = parseInt(document.getElementById('product-price').value);
                const quantity = parseInt(document.getElementById('product-quantity').value);
                const category = document.getElementById('product-category').value;
                const description = document.getElementById('product-description').value;
                const code = generateProductCode();
                
                const newProduct = {
                    code,
                    name,
                    price,
                    quantity,
                    category,
                    description
                };
                
                products.push(newProduct);
                saveData();
                renderProducts(categoryFilter.value);
                
                // نمایش کد محصول
                document.getElementById('generated-code').textContent = code;
                productCodeDisplay.classList.remove('hidden');
                
                // پاک کردن فرم
                productForm.reset();
                
                showNotification('محصول جدید با موفقیت ثبت شد');
                
                // بررسی و اضافه کردن دسته‌بندی جدید
                if (!categories.includes(category)) {
                    categories.push(category);
                    updateCategoryFilter();
                }
            });
            
            // جستجوی محصول
            searchBtn.addEventListener('click', searchProduct);
            
            // فروش محصول
            sellBtn.addEventListener('click', sellProduct);
            
            // فیلتر دسته‌بندی‌ها
            categoryFilter.addEventListener('change', function() {
                renderProducts(this.value);
            });
            
            // بستن مودال
            document.getElementById('close-modal').addEventListener('click', closeModal);
            document.getElementById('cancel-btn').addEventListener('click', closeModal);
        });
    </script>
</body>
</html>