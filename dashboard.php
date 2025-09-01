<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه هادی - داشبورد مدیریت</title>
    <script src="tailwind.js"></script>
    <link href="css-library.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="color.css">

    <style>
        * {
            font-family: peyda;
        }

        body {
            font-family: peyda;
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



        <!-- Main Content -->
        <div class="flex-1 overflow-auto relative">
            <div id="overlay" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden"></div>
            <!-- Top Navigation -->
            <?php include("header.php"); ?>

            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-6">
<?php
// تابع ساده برای گرفتن سال و ماه شمسی جاری
function getCurrentJalaliYearMonth() {
    $tz = new DateTimeZone('Asia/Tehran');
    $date = new DateTime('now', $tz);
    $gy = $date->format('Y');
    $gm = $date->format('m');
    $gd = $date->format('d');

    // تبدیل میلادی به شمسی
    list($jy, $jm, $jd) = gregorian_to_jalali($gy, $gm, $gd);
    return array($jy, $jm); // بازگشت سال و ماه جدا
}

// تابع تبدیل میلادی به شمسی
function gregorian_to_jalali($g_y, $g_m, $g_d) {
    $g_days_in_month = array(31,28,31,30,31,30,31,31,30,31,30,31);
    $j_days_in_month = array(31,31,31,31,31,31,30,30,30,30,30,29);
    $gy = $g_y-1600;
    $gm = $g_m-1;
    $gd = $g_d-1;
    $g_day_no = 365*$gy + intval(($gy+3)/4) - intval(($gy+99)/100) + intval(($gy+399)/400);
    for ($i=0;$i<$gm;$i++)
        $g_day_no += $g_days_in_month[$i];
    if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
        $g_day_no++;
    $g_day_no += $gd;
    $j_day_no = $g_day_no-79;
    $j_np = intval($j_day_no/12053);
    $j_day_no %= 12053;
    $jy = 979+33*$j_np + 4*intval($j_day_no/1461);
    $j_day_no %= 1461;
    if ($j_day_no >= 366) {
        $jy += intval(($j_day_no-1)/365);
        $j_day_no = ($j_day_no-1)%365;
    }
    for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; $i++)
        $j_day_no -= $j_days_in_month[$i];
    $jm = $i+1;
    $jd = $j_day_no+1;
    return array($jy, $jm, $jd);
}

// گرفتن سال و ماه جاری شمسی
list($currentYear, $currentMonth) = getCurrentJalaliYearMonth();
$monthLike = "$currentYear/$currentMonth/%";
$yearLike = "$currentYear/%";

// اتصال به دیتابیس
$conn = new mysqli("localhost", "root", "", "salam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- داده‌ها ---
// فروش روزانه
$sqlDailySales = "SELECT COUNT(*) as total FROM products WHERE DATE(date_added) = CURRENT_DATE()";
$dailySales = $conn->query($sqlDailySales)->fetch_assoc()["total"] ?? 0;

// درآمد روزانه
$sqlDailyRevenue = "SELECT SUM(price) AS total_price FROM products WHERE DATE(date_added) = CURRENT_DATE()";
$dailyRevenue = $conn->query($sqlDailyRevenue)->fetch_assoc()["total_price"] ?? 0;

// فروش ماهانه
$sqlMonthlySales = "SELECT COUNT(*) as total FROM products WHERE date LIKE '$monthLike'";
$monthlySales = $conn->query($sqlMonthlySales)->fetch_assoc()["total"] ?? 0;

// درآمد ماهانه
$sqlMonthlyRevenue = "SELECT SUM(price) AS total_price FROM products WHERE date LIKE '$monthLike'";
$monthlyRevenue = $conn->query($sqlMonthlyRevenue)->fetch_assoc()["total_price"] ?? 0;

// فروش سالانه
$sqlAnnualSales = "SELECT COUNT(*) as total FROM products WHERE date LIKE '$yearLike'";
$annualSales = $conn->query($sqlAnnualSales)->fetch_assoc()["total"] ?? 0;

// --- محاسبه تغییرات نسبت به دوره قبل ---
// فروش روز گذشته
$sqlYesterdaySales = "SELECT COUNT(*) as total FROM products WHERE DATE(date_added) = DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)";
$yesterdaySales = $conn->query($sqlYesterdaySales)->fetch_assoc()["total"] ?? 0;
$dailySalesChange = $yesterdaySales > 0 ? (($dailySales - $yesterdaySales) / $yesterdaySales) * 100 : 0;

// درآمد روز گذشته
$sqlYesterdayRevenue = "SELECT SUM(price) as total_price FROM products WHERE DATE(date_added) = DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)";
$yesterdayRevenue = $conn->query($sqlYesterdayRevenue)->fetch_assoc()["total_price"] ?? 0;
$dailyRevenueChange = $yesterdayRevenue > 0 ? (($dailyRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 : 0;

// فروش ماه قبل
$previousMonth = $currentMonth - 1;
$previousYear = $currentYear;
if ($previousMonth == 0) {
    $previousMonth = 12;
    $previousYear -= 1;
}
$previousMonthLike = "$previousYear/$previousMonth/%";
$sqlPreviousMonthSales = "SELECT COUNT(*) as total FROM products WHERE date LIKE '$previousMonthLike'";
$previousMonthSales = $conn->query($sqlPreviousMonthSales)->fetch_assoc()["total"] ?? 0;
$monthlySalesChange = $previousMonthSales > 0 ? (($monthlySales - $previousMonthSales) / $previousMonthSales) * 100 : 0;

// درآمد ماه قبل
$sqlPreviousMonthRevenue = "SELECT SUM(price) as total_price FROM products WHERE date LIKE '$previousMonthLike'";
$previousMonthRevenue = $conn->query($sqlPreviousMonthRevenue)->fetch_assoc()["total_price"] ?? 0;
$monthlyRevenueChange = $previousMonthRevenue > 0 ? (($monthlyRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 : 0;

// فروش سال قبل
$sqlPreviousYearSales = "SELECT COUNT(*) as total FROM products WHERE date LIKE '".($currentYear-1)."%' ";
$previousYearSales = $conn->query($sqlPreviousYearSales)->fetch_assoc()["total"] ?? 0;
$annualSalesChange = $previousYearSales > 0 ? (($annualSales - $previousYearSales) / $previousYearSales) * 100 : 0;

$conn->close();
?>

<!-- کارت‌ها -->
<?php
function renderCard($title, $value, $change, $iconColor, $bgColor, $unit = '', $suffix = '') {
    $colorClass = $change >= 0 ? 'text-green-500' : 'text-red-500';
    $changeText = number_format($change, 1)."%";
    echo "
    <div class='glass-card p-6 rounded-xl'>
        <div class='flex justify-between items-start'>
            <div>
                <p class='text-gray-500'>{$title}</p>
                <h3 class='text-2xl font-bold mt-2'>{$value}{$unit}{$suffix}</h3>
                <p class='text-sm {$colorClass} mt-2 flex items-center'>
                    <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 ml-1' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                        <path d='M5 10l7-7 7 7M5 20h14'/>
                    </svg>
                    <span>{$changeText} نسبت به دوره قبل</span>
                </p>
            </div>
            <div class='bg-{$bgColor} p-3 rounded-lg'>
                <svg xmlns='http://www.w3.org/2000/svg' class='w-6 h-6 text-{$iconColor}' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                    <path d='M12 8c-2.28 0-4 1.72-4 4s1.72 4 4 4 4-1.72 4-4-1.72-4-4-4z'/>
                    <path d='M12 2v2m0 16v2m10-10h-2M4 12H2m15.54 7.54l-1.41-1.41M6.87 6.87 5.46 5.46m12.73 0-1.41 1.41M6.87 17.13l-1.41 1.41'/>
                </svg>
            </div>
        </div>
    </div>
    ";
}

// رندر کارت‌ها
renderCard("فروش روزانه", $dailySales, $dailySalesChange, "orange-500", "orange-100");
renderCard("فروش ماهانه", $monthlySales, $monthlySalesChange, "blue-500", "blue-100");
renderCard("فروش سالانه ({$currentYear})", $annualSales, $annualSalesChange, "red-500", "red-100");
renderCard("درآمد روزانه", number_format($dailyRevenue, 0, '.', ','), $dailyRevenueChange, "yellow-500", "yellow-100", ",000");
renderCard("درآمد ماهانه", number_format($monthlyRevenue, 0, '.', ','), $monthlyRevenueChange, "green-500", "green-100", ",000");
?>




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
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
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
                                    $sql = "SELECT name, COUNT(*) AS count FROM products WHERE date LIKE '1404/6/%' GROUP BY name ORDER BY count DESC LIMIT 5";
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
                    $dbname = "salam";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("اتصال به دیتابیس ناموفق بود: " . $conn->connect_error);
                    }

                    // اجرای پرس و جو برای دریافت داده‌ها
                    $sql = "SELECT name, price FROM chart ORDER BY id ASC";
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
                                                family: 'peyda'
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>





<!-- Modal (پاپ آپ) -->
<div id="addProductModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <h2 class="text-lg font-bold mb-4">افزودن محصول جدید</h2>
        <form id="addProductForm" method="POST" action="insert_product.php" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">نام محصول</label>
                <input type="text" name="name" required
                    class="w-full bg-gray-100 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>  
            <div>
                <label class="block text-sm font-medium text-gray-700">قیمت خرید (فی)</label>
                <input type="number" name="unit_price" required
                    class="w-full bg-gray-100 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">انصراف</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">ثبت</button>
            </div>
        </form>
    </div>
</div>

<script>
    const productSelect = document.getElementById("productSelect");
    const modal = document.getElementById("addProductModal");

    productSelect.addEventListener("change", function () {
        if (this.value === "add_new") {
            modal.classList.remove("hidden");
            this.value = ""; // برگرداندن انتخاب به حالت خالی
        }
    });

    function closeModal() {
        modal.classList.add("hidden");
    }
</script>

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
                                    <th class="pb-2 text-center">روش پرداخت</th>
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
        echo '<td class="py-3 text-center">' . htmlspecialchars($row['payment_method']) . '</td>';
        echo '<td class="py-3 text-center">';
        
        echo '<div class="flex items-center gap-2">';

        // دکمه حذف
        echo '<a href="delete_product.php?id=' . $row['id'] . '" class="text-red-500 hover:text-red-700 transition duration-200" title="حذف">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#EF4444"><path fill="none" stroke="#EF4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/></svg>';
        echo '</a>';
        
        // دکمه نمایش فاکتور
        echo '<button onclick="showInvoice(\'' . htmlspecialchars($row['name']) . '\', \'' . htmlspecialchars($row['size']) . '\', \'' . htmlspecialchars($row['color']) . '\', \'' . htmlspecialchars($row['date']) . '\', \'' . number_format(floatval($row['price']), 0, '.', ',') . '\', \'' . $row['id'] . '\')" class="text-blue-500 hover:text-blue-700 transition duration-200" title="نمایش فاکتور">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563EB"><g fill="none" stroke="#2563EB" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></g></svg>';
        echo '</button>';
        
        echo '</div>';
        

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



<!-- کد های پاپ آپ برای پرینت -->

<!-- Modal -->
<div id="invoiceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center  items-center z-50 ">
  <div class="bg-white w-11/12 md:w-2/3 lg:w-1/2 rounded-2xl shadow-xl p-6 relative">
    
    <!-- دکمه بستن -->
    <button onclick="closeInvoice()" 
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
    
    <div id="invoiceContent" class="text-gray-800">
      <!-- محتوای فاکتور اینجا با جاوااسکریپت پر میشه -->
    </div>

    <!-- دکمه پرینت -->
    <div class="text-center mt-6">
      <button onclick="printInvoice()" 
              class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
        پرینت فاکتور
      </button>
    </div>
  </div>
</div>


<script>
function showInvoice(name, size, color, date, price , id) {
    const invoiceHTML = `
<div class="border border-gray-400 p-4 rounded-xl w-100% mx-auto">
        <!-- هدر فروشگاه -->
        <div class="text-center mb-4">
          <h2 class="text-xl font-bold">فروشگاه هادی</h2>
          <p class="text-sm text-gray-600">بورس کت شلوار و کت دامن</p>
        </div>

        <!-- اطلاعات فاکتور -->
        <div class="flex justify-between text-sm mb-4">
          <p><strong>شماره فاکتور:</strong> ${id}</p>
          <p><strong>تاریخ :</strong> ${date}</p>
        </div>

        <!-- جدول محصولات -->
        <table class="w-full border text-sm text-center mb-4">
          <thead>
            <tr class="bg-gray-100">
              <th class="border px-2 py-1">نام کالا</th>
              <th class="border px-2 py-1">سایز</th>
              <th class="border px-2 py-1">رنگ</th>
              <th class="border px-2 py-1">تعداد</th>
              <th class="border px-2 py-1">قیمت واحد</th>
              <th class="border px-2 py-1">مبلغ کل</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="border px-2 py-1">${name}</td>
              <td class="border px-2 py-1">${size}</td>
              <td class="border px-2 py-1">${color}</td>
              <td class="border px-2 py-1">1</td>
              <td class="border px-2 py-1">${price},000</td>
              <td class="border px-2 py-1">${price},000</td>
            </tr>
          </tbody>
        </table>

        <!-- بخش جمع کل -->
        <div class="border p-3 mb-4">
          <p><strong>مبلغ فاکتور:</strong> ${price},000 ریال</p>
          <p><strong>جمع کل:</strong> ${price},000 تومان</p>
        </div>

        <!-- اطلاعات تماس -->
        <div class="text-center text-sm">
          <p>📍 تبریز , بازار , میدان نماز , پاساژ نماز پلاک 3</p>
          <p>☎️ 041-35236433 | 📱 09911631448</p>
          <p class="mt-2 text-gray-500">تفاوت قیمت را با ما تجربه کنید</p>
        </div>
      </div>
    `;
    document.getElementById("invoiceContent").innerHTML = invoiceHTML;
    document.getElementById("invoiceModal").classList.remove("hidden");
    document.getElementById("invoiceModal").classList.add("flex");
}

function closeInvoice() {
    document.getElementById("invoiceModal").classList.add("hidden");
    document.getElementById("invoiceModal").classList.remove("flex");
}

function printInvoice() {
    const printContents = document.getElementById("invoiceContent").innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>




    <script src="scripts.js"></script>
</body>

</html>