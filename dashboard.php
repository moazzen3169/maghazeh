<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه هادی - داشبورد مدیریت</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="color.css">

    <style>
        * {
            font-family: modam;
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

        .product-table tr:nth-child(even) {
            background-color: var(--color-even-row);
        }

        .product-table tr:hover {
            background-color: var(--color-hover-row);
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px var(--color-input-focus);
        }

        #date-container {
            margin-left: 30px;
            color: var(--color-text);
        }
    </style>
</head>

<body class="bg-gray-50   ">
    <div class="flex flex-col md:flex-row h-screen overflow-hidden">
        <!-- side bar-->
        <?php include("sidebar.php"); ?>


        <!-- Mobile Sidebar Toggle -->
        <div id="mobileSidebar"
            class="fixed inset-0 z-40 bg-white transform -translate-x-full transition-transform duration-300 md:hidden">
            <div class="p-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-800 text-center">فروشگاه هادی</h1>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="#" class="sidebar-item flex items-center p-3 rounded-lg text-blue-600 bg-blue-50">
                    <i class="fas fa-chart-line ml-2"></i>
                    <span>داشبورد</span>
                </a>
                <a href="products.php"
                    class="sidebar-item flex items-center p-3 rounded-lg text-gray-600 hover:text-blue-600">
                    <i class="fas fa-box ml-2"></i>
                    <span>محصولات</span>
                </a>
                <a href="factor.php"
                    class="sidebar-item flex items-center p-3 rounded-lg text-gray-600 hover:text-blue-600">
                    <i class="fas fa-file-invoice-dollar ml-2"></i>
                    <span>فاکتورها</span>
                </a>
                <a href="pay.php"
                    class="sidebar-item flex items-center p-3 rounded-lg text-gray-600 hover:text-blue-600">
                    <i class="fas fa-users ml-2"></i>
                    <span>پرداخت ها</span>
                </a>
                <a href="#" class="sidebar-item flex items-center p-3 rounded-lg text-gray-600 hover:text-blue-600">
                    <i class="fas fa-cog ml-2"></i>
                    <span>تنظیمات</span>
                </a>
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

        <!-- Main Content -->
        <div class="flex-1 overflow-auto relative">
            <div id="overlay" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden"></div>
            <!-- Top Navigation -->
            <?php include("header.php"); ?>

            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-6">
                    <!-- Daily Revenue -->



                    <!-- Daily Sales -->
                    <div class="glass-card p-6 rounded-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">فروش روزانه</p>
                                <h3 class="text-2xl font-bold mt-2">
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "salam");
                                    $sql = "SELECT COUNT(*) as total FROM products WHERE DATE(date_added) = CURRENT_DATE()";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row["total"];
                                    $conn->close();
                                    ?>
                                </h3>
                                <p class="text-sm text-green-500 mt-2">
                                    <i class="fas fa-arrow-up ml-1"></i>
                                    <span>3.8% نسبت به دیروز</span>
                                </p>
                            </div>
                            <div class="bg-orange-100 p-3 rounded-lg">
                                <i class="fas fa-calendar-day text-orange-500 text-xl"></i>
                            </div>
                        </div>
                    </div>




                    <!-- Monthly Sales -->
                    <div class="glass-card p-6 rounded-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">فروش ماهانه</p>
                                <h3 class="text-2xl font-bold mt-2">
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "salam");
                                    $sql = "SELECT COUNT(*) as total FROM products WHERE date LIKE '1404/3/%'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row["total"];
                                    $conn->close();
                                    ?>
                                </h3>
                                <p class="text-sm text-green-500 mt-2">
                                    <i class="fas fa-arrow-up ml-1"></i>
                                    <span>5.2% نسبت به ماه قبل</span>
                                </p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-shopping-bag text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Annual Sales -->
                    <div class="glass-card p-6 rounded-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">فروش سالانه(1404) </p>
                                <h3 class="text-2xl font-bold mt-2">
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "salam");
                                    $sql = "SELECT COUNT(*) as total FROM products WHERE date LIKE '1404/%/%'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row["total"];
                                    $conn->close();
                                    ?>
                                </h3>
                                <p class="text-sm text-green-500 mt-2">
                                    <i class="fas fa-arrow-up ml-1"></i>
                                    <span>15.3% نسبت به سال قبل</span>
                                </p>
                            </div>
                            <div class="bg-red-100 p-3 rounded-lg">
                                <i class="fas fa-calendar-alt text-red-500 text-xl"></i>
                            </div>
                        </div>
                    </div>


                    <div class="glass-card p-6 rounded-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">درآمد روزانه</p>
                                <h3 class="text-2xl font-bold mt-2">
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "salam");
                                    $sql = "SELECT SUM(price) AS total_price FROM products WHERE DATE(date_added) = CURRENT_DATE()";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    $total = $row["total_price"] ?? 0;
                                    echo number_format($total, 0, '.', ',') . ",000";
                                    $conn->close();
                                    ?>
                                </h3>
                                <p class="text-sm text-green-500 mt-2">
                                    <i class="fas fa-arrow-up ml-1"></i>
                                    <span>8.4% نسبت به دیروز</span>
                                </p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-lg">
                                <i class="fas fa-money-bill-wave text-yellow-500 text-xl"></i>
                            </div>
                        </div>
                    </div>




                    <!-- Monthly Revenue -->
                    <div class="glass-card p-6 rounded-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">درآمد ماهانه</p>
                                <h3 class="text-2xl font-bold mt-2">
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "salam");
                                    $sql = "SELECT SUM(price) AS total_price FROM products WHERE date LIKE '1404/3/%'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo number_format($row["total_price"], 0, '.', ',') . ",000";
                                    $conn->close();
                                    ?>
                                </h3>
                                <p class="text-sm text-green-500 mt-2">
                                    <i class="fas fa-arrow-up ml-1"></i>
                                    <span>12.7% نسبت به ماه قبل</span>
                                </p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-wallet text-green-500 text-xl"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Top Products -->
                    <div class="glass-card p-6 rounded-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">محصولات پرفروش</p>
                                <h3 class="text-2xl font-bold mt-2">
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "salam");
                                    $sql = "SELECT name FROM products WHERE date LIKE '1404/3/%' GROUP BY name ORDER BY COUNT(*) DESC LIMIT 1";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row["name"] ?? "N/A";
                                    $conn->close();
                                    ?>
                                </h3>
                                <p class="text-sm text-gray-500 mt-2">پرفروش ترین محصول</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-star text-purple-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Tables -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                    <!-- Sales Chart -->
                    <div class="lg:col-span-2 glass-card p-6 rounded-xl">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-3 mb-4">
                            <h3 class="font-semibold text-gray-800">نمودار فروش ماهانه</h3>

                            <div class="flex items-center gap-2">
                                <!-- انتخاب سال -->
                                <select
                                    class="bg-gray-100 border-0 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option>سال جاری</option>
                                </select>

                                <!-- فرم افزودن داده -->
                                <form method="POST" action="insert_data.php" class="flex items-center gap-2">
                                    <select name="month" class="bg-gray-100 border rounded-lg px-2 py-1 text-sm">
                                        <option value="فروردین">فروردین</option>
                                        <option value="اردیبهشت">اردیبهشت</option>
                                        <option value="خرداد">خرداد</option>
                                        <option value="تیر">تیر</option>
                                        <option value="مرداد">مرداد</option>
                                        <option value="شهریور">شهریور</option>
                                        <option value="مهر">مهر</option>
                                        <option value="آبان">آبان</option>
                                        <option value="آذر">آذر</option>
                                        <option value="دی">دی</option>
                                        <option value="بهمن">بهمن</option>
                                        <option value="اسفند">اسفند</option>
                                    </select>
                                    <input type="number" name="price" placeholder="مبلغ"
                                        class="bg-gray-100 border rounded-lg px-2 py-1 w-24 text-sm" required />
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-600">+</button>
                                </form>
                            </div>
                        </div>

                        <div class="h-64">
                            <!-- Chart Canvas -->
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Top Products Table -->
                    <div class="glass-card p-6 rounded-xl">
                        <h3 class="font-semibold text-gray-800 mb-4">محصولات پرفروش</h3>
                        <div class="overflow-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-gray-500 border-b border-gray-200">
                                        <th class="pb-2 text-right">محصول</th>
                                        <th class="pb-2 text-center">تعداد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "salam");
                                    $sql = "SELECT name, COUNT(*) AS count FROM products WHERE date LIKE '1404/3/%' GROUP BY name ORDER BY count DESC LIMIT 5";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr class="border-b border-gray-100">';
                                            echo '<td class="py-3 text-right">' . $row["name"] . '</td>';
                                            echo '<td class="py-3 text-center">' . $row["count"] . '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="2" class="py-3 text-center text-gray-500">داده ای یافت نشد</td></tr>';
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Chart.js Library -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    // اتصال به دیتابیس و دریافت داده‌ها برای نمودار
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "test";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("اتصال به دیتابیس ناموفق بود: " . $conn->connect_error);
                    }

                    // اجرای پرس و جو برای دریافت داده‌ها
                    $sql = "SELECT name, price FROM products ORDER BY id ASC";
                    $result = $conn->query($sql);

                    $labels = [];
                    $prices = [];

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $labels[] = $row["name"];
                            $prices[] = $row["price"];
                        }
                    }

                    $conn->close();

                    // تبدیل آرایه‌ها به JSON
                    $labelsJSON = json_encode($labels, JSON_UNESCAPED_UNICODE);  // برای نمایش فارسی
                    $pricesJSON = json_encode($prices);
                    ?>


                    // ایجاد نمودار
                    document.addEventListener('DOMContentLoaded', function () {
                        const labelsFromDB = <?php echo $labelsJSON; ?>;
                        const dataFromDB = <?php echo $pricesJSON; ?>;

                        const ctx = document.getElementById('salesChart').getContext('2d');
                        const salesChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labelsFromDB,
                                datasets: [{
                                    label: 'میزان فروش',
                                    data: dataFromDB,
                                    backgroundColor: 'rgba(78, 165, 223, 0.86)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function (value) {
                                                return value.toLocaleString('fa-IR');
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            font: {
                                                family: 'modam'
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

                <!-- Add Product Form -->
                <!-- Add Product Form -->
                <div class="glass-card p-6 rounded-xl mb-6">
                    <h3 class="font-semibold text-gray-800 mb-6 text-lg border-b pb-3">ثبت محصول جدید</h3>
                    <form method="post" action="add.php" class="grid gap-6">
                        <!-- ردیف اول - فیلدهای ورودی -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
                            <!-- نام محصول -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">نام محصول</label>
                                <select name="name"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                                    <option value="دلبر">دلبر</option>
                                    <option value="باتری">باتری</option>
                                    <option value="سرو">سرو</option>
                                    <option value="شراره">شراره</option>
                                    <option value="فارکس">فارکس</option>
                                    <option value="چهار دکمه">چهار دکمه</option>
                                    <option value="تک دکمه">تک دکمه</option>
                                    <option value="سرهم">سرهم</option>
                                    <option value="ژاکات">ژاکات</option>
                                    <option value="نفیس">نفیس</option>
                                    <option value="عروس">عروس</option>
                                    <option value="پاپیونی">پاپیونی</option>
                                    <option value="یقه انگیلیسی">یقه انگلیسی</option>
                                    <option value="کمر دار">کمردار</option>
                                    <option value="تهران جدید">تهران جدید</option>
                                    <option value="منج دوزی">منج دوزی</option>
                                    <option value="جدید راه راه">جدید راه راه</option>
                                    <option value="ارشال">ارشال</option>
                                    <option value="جدید">جدید</option>
                                    <option value="(کت دامن)">(کت دامن)</option>
                                    <option value="کت تکی">کت تکی</option>
                                </select>
                            </div>

                            <!-- رنگ -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">رنگ</label>
                                <select name="color"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                                    <option value="مشکی">مشکی</option>
                                    <option value="سفید">سفید</option>
                                    <option value="قرمز">قرمز</option>
                                    <option value="سبز">سبز</option>
                                    <option value="زرد">زرد</option>
                                    <option value="خردلی">خردلی</option>
                                    <option value="کرمی">کرمی</option>
                                    <option value="قهوه ای">قهوه ای</option>
                                    <option value="صورتی">صورتی</option>
                                    <option value="زرشکی">زرشکی</option>
                                    <option value="توسی">توسی</option>
                                    <option value="گلبهی">گلبهی</option>
                                    <option value="بنفش">بنفش</option>
                                    <option value="آبی">آبی</option>
                                    <option value="تعویضی">تعویضی</option>
                                </select>
                            </div>

                            <!-- سایز -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">سایز</label>
                                <select name="size"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                                    <option value="36">36</option>
                                    <option value="38">38</option>
                                    <option value="40">40</option>
                                    <option value="42">42</option>
                                    <option value="44">44</option>
                                    <option value="46">46</option>
                                    <option value="48">48</option>
                                    <option value="50">50</option>
                                    <option value="52">52</option>
                                    <option value="54">54</option>
                                    <option value="56">56</option>
                                </select>
                            </div>

                            <!-- تاریخ -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">تاریخ</label>
                                <input type="text" name="date" id="date-input"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <!-- قیمت -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">قیمت</label>
                                <input type="text" name="price" placeholder="قیمت"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- ردیف دوم - دکمه ثبت -->
                        <div class="grid grid-cols-1">
                            <button type="submit"
                                class="w-full md:w-3/3 mx-auto bg-blue-500 hover:bg-blue-600 text-white py-2.5 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-plus ml-2"></i>
                                <span>ثبت محصول</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Search and Products Table -->
                <div class="glass-card p-6 rounded-xl">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-lg">لیست محصولات</h3>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle ml-1"></i>
                                <?php
                                $conn = new mysqli("localhost", "root", "", "salam");

                                // Total products count
                                $totalSql = "SELECT COUNT(*) as total FROM products";
                                $totalResult = $conn->query($totalSql);
                                $totalRow = $totalResult->fetch_assoc();
                                $totalProducts = $totalRow['total'];

                                // Search results count and sum
                                $searchCount = 0;
                                $searchSum = 0;

                                if (isset($_GET['search'])) {
                                    $searchTerm = $_GET['search'];
                                    $countSql = "SELECT COUNT(*) as count, SUM(price) as sum FROM products WHERE name LIKE '%$searchTerm%' or price LIKE '%$searchTerm%' or size LIKE '%$searchTerm%' or date LIKE '%$searchTerm%' or color LIKE '%$searchTerm%'";
                                    $countResult = $conn->query($countSql);
                                    $countRow = $countResult->fetch_assoc();
                                    $searchCount = $countRow['count'];
                                    $searchSum = $countRow['sum'] ? $countRow['sum'] : 0;
                                }

                                echo '<span>تعداد کل محصولات: ' . $totalProducts . '</span>';

                                if (isset($_GET['search']) && $searchCount > 0) {
                                    echo '<span class="mx-2">|</span>';
                                    echo '<span class="text-blue-500">تعداد نتایج جستجو: ' . $searchCount . '</span>';
                                    echo '<span class="mx-2">|</span>';
                                    echo '<span class="text-green-500">جمع قیمت: ' . number_format($searchSum, 0, '.', ',') . ',000 تومان</span>';
                                }

                                $conn->close();
                                ?>
                            </div>
                        </div>
                        <form method="GET" class="w-full md:w-auto mt-4 md:mt-0">
                            <div class="relative">
                                <input type="text" name="search" placeholder="جستجو..."
                                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"
                                    class="bg-gray-100 border-0 rounded-lg px-4 py-2 pr-10 w-full md:w-64 focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="absolute left-3 top-2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto relative">
                        <div class="md:hidden px-4 py-3 text-sm text-gray-500">
                            برای مشاهده کامل جدول، به راست یا چپ اسکرول کنید
                        </div>
                        <table class="w-full product-table">
                            <thead>
                                <tr class="text-gray-500 border-b border-gray-200">
                                    <th class="pb-2 text-right">نام</th>
                                    <th class="pb-2 text-center">سایز</th>
                                    <th class="pb-2 text-center">رنگ</th>
                                    <th class="pb-2 text-center">تاریخ</th>
                                    <th class="pb-2 text-center">قیمت</th>
                                    <th class="pb-2 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body">
                                <?php
                                $conn = new mysqli("localhost", "root", "", "salam");

                                // تعیین محدودیت نمایش
                                $limit = isset($_GET['show_all']) ? 1000 : 5;

                                if (isset($_GET['search'])) {
                                    $searchTerm = $conn->real_escape_string($_GET['search']);
                                    $sql = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' OR price LIKE '%$searchTerm%' OR size LIKE '%$searchTerm%' OR date LIKE '%$searchTerm%' OR color LIKE '%$searchTerm%' ORDER BY id DESC LIMIT $limit";
                                } else {
                                    $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $limit";
                                }

                                $result = $conn->query($sql);
                                $total_rows = $result->num_rows;

                                if ($total_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr class="border-b border-gray-100 hover:bg-gray-50">';
                                        echo '<td class="py-3 text-right">' . htmlspecialchars($row['name']) . '</td>';
                                        echo '<td class="py-3 text-center">' . htmlspecialchars($row['size']) . '</td>';
                                        echo '<td class="py-3 text-center">' . htmlspecialchars($row['color']) . '</td>';
                                        echo '<td class="py-3 text-center">' . htmlspecialchars($row['date']) . '</td>';
                                        echo '<td class="py-3 text-center">' . number_format(floatval($row['price']), 0, '.', ',') . ',000 تومان</td>';
                                        echo '<td class="py-3 text-center">';
                                        echo '<a href="delete_product.php?id=' . $row['id'] . '" class="text-red-500 hover:text-red-700 transition duration-200" title="حذف">';
                                        echo '<i class="fas fa-trash-alt"></i>';
                                        echo '</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }

                                    // اگر محدودیت وجود دارد و رکوردهای بیشتری موجود است
                                    if (!isset($_GET['show_all'])) {
                                        $count_sql = isset($_GET['search']) ?
                                            "SELECT COUNT(*) as total FROM products WHERE name LIKE '%$searchTerm%' OR price LIKE '%$searchTerm%' OR size LIKE '%$searchTerm%' OR date LIKE '%$searchTerm%' OR color LIKE '%$searchTerm%'" :
                                            "SELECT COUNT(*) as total FROM products";

                                        $count_result = $conn->query($count_sql);
                                        $total_count = $count_result->fetch_assoc()['total'];

                                        if ($total_count > 5) {
                                            echo '<tr id="show-more-row">';
                                            echo '<td colspan="6" class="py-4 text-center">';
                                            $query_params = $_GET;
                                            $query_params['show_all'] = '1';
                                            echo '<a href="?' . htmlspecialchars(http_build_query($query_params)) . '" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">';
                                            echo 'مشاهده همه محصولات';
                                            echo '</a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="py-4 text-center text-gray-500">محصولی یافت نشد</td></tr>';
                                }

                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>

</html>