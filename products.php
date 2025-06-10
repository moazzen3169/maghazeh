<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه هادی - مدیریت محصولات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --color-bg: #f5f7fa;
            --color-card-bg: rgba(255, 255, 255, 0.8);
            --color-hover-bg: rgba(59, 130, 246, 0.1);
            --color-hover-row: rgba(59, 130, 246, 0.05);
            --color-even-row: rgba(249, 250, 251, 0.8);
            --color-shadow: rgba(0, 0, 0, 0.05);
            --color-input-focus: rgba(59, 130, 246, 0.2);
            --color-text: #374151;
        }

        body.dark {
            --color-bg: #1f2937;
            --color-card-bg: rgba(30, 41, 59, 0.8);
            --color-hover-bg: rgba(147, 197, 253, 0.1);
            --color-hover-row: rgba(147, 197, 253, 0.05);
            --color-even-row: rgba(31, 41, 55, 0.8);
            --color-shadow: rgba(0, 0, 0, 0.3);
            --color-input-focus: rgba(147, 197, 253, 0.2);
            --color-text: #f3f4f6;
        }

        body {
            font-family: modam;
            background-color: var(--color-bg);
        }

        .glass-card {
            background: var(--color-card-bg);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 4px 6px var(--color-shadow);
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            background-color: var(--color-hover-bg);
        }

        .product-card {
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        #date-container {
            margin-left: 30px;
            color: var(--color-text);
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include("sidebar.php");?>


        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Navigation -->
            <?php include("header.php");?>

            

            <!-- Products Content -->
            <main class="p-6">


                <!-- Products Stats -->
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Total Products -->
                        <div class="glass-card p-6 rounded-xl">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500">تعداد نوع محصولات</p>
                                    <h3 class="text-2xl font-bold mt-2">
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "salam");
                                        $sql = "SELECT COUNT(DISTINCT name) as total FROM products";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                        echo $row["total"];
                                        $conn->close();
                                        ?>
                                    </h3>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="fas fa-boxes text-blue-500 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Sales -->
                        <div class="glass-card p-6 rounded-xl">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500">تعداد کل فروش</p>
                                    <h3 class="text-2xl font-bold mt-2">
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "salam");
                                        $sql = "SELECT COUNT(*) as total FROM products";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                        echo $row["total"];
                                        $conn->close();
                                        ?>
                                    </h3>
                                </div>
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <i class="fas fa-shopping-cart text-green-500 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Revenue -->
                        <div class="glass-card p-6 rounded-xl">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500">درآمد کل</p>
                                    <h3 class="text-2xl font-bold mt-2">
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "salam");
                                        $sql = "SELECT SUM(price) as total FROM products";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                        echo number_format($row["total"], 0, '.', ',') . ",000";
                                        $conn->close();
                                        ?>
                                    </h3>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-lg">
                                    <i class="fas fa-money-bill-wave text-yellow-500 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Profit -->
                        <div class="glass-card p-6 rounded-xl">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500">سود کل</p>
                                    <h3 class="text-2xl font-bold mt-2">
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "salam");

                                        // Get total revenue
                                        $sql = "SELECT SUM(price) as total_revenue FROM products";
                                        $result = $conn->query($sql);
                                        $revenue = $result->fetch_assoc()["total_revenue"];

                                        // Get total cost (we need to create a product_prices table for this)
                                        // This is a placeholder - you'll need to implement this properly
                                        $total_cost = 0; // Placeholder
                                        
                                        $profit = $revenue - $total_cost;
                                        echo number_format($profit, 0, '.', ',') . ",000";
                                        $conn->close();
                                        ?>
                                    </h3>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-lg">
                                    <i class="fas fa-chart-pie text-purple-500 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-800 text-lg">لیست محصولات</h3>
                        <form method="GET" class="w-full md:w-auto">
                            <div class="relative">
                                <input type="text" name="search" placeholder="جستجوی محصول..."
                                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"
                                    class="bg-gray-100 border-0 rounded-lg px-4 py-2 pr-10 w-full md:w-64 focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="absolute left-3 top-2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php
                        $conn = new mysqli("localhost", "root", "", "salam");

                        // Check if search is active
                        $searchCondition = "";
                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                            $searchTerm = $conn->real_escape_string($_GET['search']);
                            $searchCondition = "WHERE name LIKE '%$searchTerm%'";
                        }

                        // Get all distinct product names
                        $sql = "SELECT DISTINCT name FROM products $searchCondition ORDER BY name";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $productName = $row['name'];

                                // Get stats for this product
                                $statsSql = "SELECT 
            COUNT(*) as sales_count, 
            SUM(price) as total_revenue 
            FROM products 
            WHERE name = '$productName'";
                                $statsResult = $conn->query($statsSql);
                                $stats = $statsResult->fetch_assoc();

                                // Get unit purchase price
                                $purchasePrice = 0;
                                $priceSql = "SELECT unit_price FROM product_prices WHERE product_name = '$productName' ORDER BY id DESC LIMIT 1";
                                $priceResult = $conn->query($priceSql);
                                if ($priceResult && $priceResult->num_rows > 0) {
                                    $priceRow = $priceResult->fetch_assoc();
                                    $purchasePrice = $priceRow['unit_price'];
                                }

                                // Calculate profit
                                $salesCount = $stats['sales_count'];
                                $totalRevenue = $stats['total_revenue'];
                                $totalCost = $salesCount * $purchasePrice;
                                $profit = $totalRevenue - $totalCost;

                                // Determine card color based on profit
                                $cardColorClass = "bg-white";
                                $profitColorClass = "text-gray-600";
                                if ($profit > 0) {
                                    $cardColorClass = "bg-whith-50";
                                    $profitColorClass = "text-green-600";
                                } elseif ($profit < 0) {
                                    $cardColorClass = "bg-red-50";
                                    $profitColorClass = "text-red-600";
                                }
                                ?>
                                <div
                                    class="product-card <?php echo $cardColorClass; ?> p-5 rounded-lg shadow-md border border-gray-100">
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="font-bold text-lg text-gray-800"><?php echo $productName; ?></h4>
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <i class="fas fa-box text-blue-500"></i>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">تعداد فروش:</span>
                                            <span class="font-medium"><?php echo $salesCount; ?></span>
                                        </div>



                                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                            <span class="text-gray-600">سود:</span>
                                            <span class="font-medium <?php echo $profitColorClass; ?>">
                                                <?php echo number_format($profit, 0, '.', ','); ?>,000 تومان
                                            </span>
                                        </div>
                                    </div>

                                    <!-- جزئیات کامل (مخفی در ابتدا) -->
                                    <div id="details-<?php echo md5($productName); ?>"
                                        class="mt-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-700 border border-gray-200 hidden">
                                        <strong>جزئیات محاسبات:</strong><br>
                                        تعداد فروش: <?php echo $salesCount; ?><br>
                                        قیمت خرید فی: <?php echo number_format($purchasePrice, 0, '.', ','); ?>,000 تومان<br>
                                        هزینه کل خرید: <?php echo number_format($totalCost, 0, '.', ','); ?>,000 تومان<br>
                                        درآمد کل: <?php echo number_format($totalRevenue, 0, '.', ','); ?>,000 تومان<br>
                                        سود: <span
                                            class="<?php echo $profitColorClass; ?>"><?php echo number_format($profit, 0, '.', ','); ?>,000
                                            تومان</span>
                                    </div>

                                    <div class="mt-4">
                                        <button onclick="toggleDetails('<?php echo md5($productName); ?>')"
                                            class="view-details-btn w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-1.5 px-3 rounded-lg text-sm transition duration-200">
                                            <i class="fas fa-list ml-1"></i>
                                            جزئیات
                                        </button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="col-span-full text-center text-gray-500 py-8">محصولی یافت نشد</div>';
                        }

                        $conn->close();
                        ?>
                    </div>

                    <script>
                        // نمایش/پنهان کردن جزئیات با کلیک
                        function toggleDetails(id) {
                            const el = document.getElementById('details-' + id);
                            if (el) {
                                el.classList.toggle('hidden');
                            }
                        }




                        // Load current date
                        fetch('https://api.keybit.ir/time/')
                            .then(response => response.json())
                            .then(data => {
                                const dateText = data.date.full.official.usual.fa;
                                document.getElementById('date-container').innerText = `تاریخ امروز: ${dateText}`;
                            })
                            .catch(error => {
                                console.error('خطا در دریافت تاریخ:', error);
                                document.getElementById('date-container').innerText = 'خطا در دریافت تاریخ';
                            });
                    </script>
</body>

</html>








<!--

همانطور که میبینی این کد ها مربوط به صفحه داشبورد هستش من میخام صفحه پرداخت ها رو برام طراحی کنی من میخام در این صفحه هدر و سایدبار همچنان به همین شکل استفاده بشه و میخام در این صفحه در ابتدا یم فرم برای ثبت پرداخت ها باشه که اطلاعاتی مثل  نام مقصد پرداختی    مبلغ پرداخت   
تاریخ پرداخت 
دلیل پرداخت
رو بگیره و در دیتابیس ذخیره کنه میخام این کار روو با استفاده از زبان php انجام بدی و در پایین اطلاعات دیتابیس رو میزارم 
و در پایین میخام یک جدولی باشه که در آن همه اطلاعات ثبت شده نمایش داده بشه  به همراه دکمه حذف #
میخام حتما قبل از این جد.ول یک فرم جستجو هم برای این بخش به کار گرفته بشه و با تاریخ و مقصد پرداختی قابل جستجو باشه

اطلاعات دیتابیس
Table Name:
payments (پرداخت‌ها)
فیلدها
id (شناسه) - Auto-incremented primary key
destination (مقصد پرداخت) - Payment recipient name
amount (مبلغ پرداخت) - Payment amount in Rials
payment_date (تاریخ پرداخت) - Date of payment
reason (دلیل پرداخت) - Payment purpose/description
created_at (تاریخ ایجاد) - Record creation timestamp
updated_at (تاریخ بروزرسانی) - Last update timestamp