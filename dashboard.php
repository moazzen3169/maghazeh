<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه هادی - داشبورد مدیریت</title>
    <script src="tailwind.js"></script>
    <link href="css-library.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/theme.css">
</head>

<body>
    <div class="app-shell">
        <!-- side bar-->
        <?php include("sidebar.php"); ?>



        <!-- Main Content -->
        <div class="app-main-wrapper">
            <?php include("header.php"); ?>

            <!-- Dashboard Content -->
            <main class="app-main">
                <!-- Stats Cards -->
                <section class="stat-grid">
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
function renderCard($title, $value, $change, $accentClass, $suffix = '') {
    $isPositive = $change >= 0;
    $deltaClass = $isPositive ? 'positive' : 'negative';
    $changeText = number_format($change, 1) . "%";

    echo "
    <div class='glass-card stat-card'>
        <div class='flex items-start justify-between gap-4'>
            <div class='flex flex-col gap-3'>
                <span class='stat-label'>{$title}</span>
                <span class='stat-value'>{$value}{$suffix}</span>
                <span class='stat-delta {$deltaClass}'>
                    <svg xmlns=\"http://www.w3.org/2000/svg\" width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'>
                        <polyline points='18 15 12 9 6 15'></polyline>
                    </svg>
                    {$changeText}
                </span>
            </div>
            <span class='stat-icon {$accentClass}'>
                <svg xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1.6'>
                    <path d='M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm0-6v2m0 16v2m10-10h-2M4 12H2m17.66 6.66-1.41-1.41M7.75 7.75 6.34 6.34m12.02 0-1.41 1.41M7.75 16.25l-1.41 1.41' />
                </svg>
            </span>
        </div>
    </div>
    ";
}

// رندر کارت‌ها
renderCard("فروش روزانه", number_format($dailySales), $dailySalesChange, "accent-amber", " عدد");
renderCard("فروش ماهانه", number_format($monthlySales), $monthlySalesChange, "accent-sky", " عدد");
renderCard("فروش سالانه ({$currentYear})", number_format($annualSales), $annualSalesChange, "accent-rose", " عدد");
renderCard("درآمد روزانه", number_format($dailyRevenue, 0, '.', ','), $dailyRevenueChange, "accent-gold", " تومان");
renderCard("درآمد ماهانه", number_format($monthlyRevenue, 0, '.', ','), $monthlyRevenueChange, "accent-emerald", " تومان");
?>




<!-- Top Products -->
<section class="glass-card table-card spotlight-card">
    <div class="table-header spotlight-header">
        <div>
            <h3 class="section-title">محصولات پرفروش</h3>
            <p class="text-muted mt-1">پنج محصول با بیشترین فروش در ماه جاری</p>
        </div>
        <span class="stat-icon accent-rose">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="m12 2 2.09 6.26H20l-5.17 3.76 1.97 6.04L12 15.52 7.2 18.06l1.97-6.04L4 8.26h5.91z" />
            </svg>
        </span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>محصول</th>
                    <th class="text-center">تعداد</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "salam");
                $sql = "SELECT name, COUNT(*) AS count FROM products WHERE date LIKE '1404/6/%' GROUP BY name ORDER BY count DESC LIMIT 5";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row["name"]) . '</td>';
                        echo '<td class="text-center">' . number_format($row["count"]) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="2" class="table-empty-state">داده‌ای برای نمایش وجود ندارد</td></tr>';
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</section>


                <!-- Charts and Tables -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                    <!-- Sales Chart -->
                    <div class="lg:col-span-2 glass-card">
                        <div class="section-heading">
                            <div>
                                <h3>نمودار فروش ماهانه</h3>
                                <p class="text-muted mt-1">بررسی روند فروش ثبت شده در جدول chart</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <select class="form-control" style="width: 140px;">
                                    <option>سال جاری</option>
                                </select>

                                <form method="POST" action="insert_data.php" class="flex items-center gap-2">
                                    <select name="month" class="form-control" style="width: 140px;">
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
                                    <input type="number" name="price" placeholder="مبلغ" class="form-control" style="width: 140px;" required />
                                    <button type="submit" class="btn btn-primary" aria-label="افزودن داده جدید">+</button>
                                </form>
                            </div>
                        </div>

                        <div class="h-64">
                            <!-- Chart Canvas -->
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Top Products Table -->
                    <div class="glass-card">
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
                <div class="glass-card">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-lg">لیست محصولات</h3>
                            <div class="flex items-center mt-2 text-sm text-gray-500 gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><g fill="none"><circle cx="12" cy="12" r="9.25" stroke="#2563eb" stroke-width="1.5"/><path stroke="#2563eb" stroke-linecap="round" stroke-width="1.5" d="M12 11.813v5"/><circle cx="12" cy="8.438" r="1.25" fill="#2563eb"/></g></svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><path fill="#currentColor" d="M15.096 5.904a6.5 6.5 0 1 0-9.192 9.192a6.5 6.5 0 0 0 9.192-9.192ZM4.49 4.49a8.5 8.5 0 0 1 12.686 11.272l5.345 5.345l-1.414 1.414l-5.345-5.345A8.501 8.501 0 0 1 4.49 4.49Z"/></svg>
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

        // دکمه حذف با مودال تایید
        echo '<button onclick="confirmDelete(' . $row["id"] . ')" class="text-red-500 hover:text-red-700 transition duration-200" title="حذف">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#EF4444"><path fill="none" stroke="#EF4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/></svg>';
        echo '</button>';


        // دکمه ویرایش
        echo '<button onclick="editProduct(' . $row['id'] . ')" class="text-green-500 hover:text-green-700 transition duration-200" title="ویرایش">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#10B981"><g fill="none" stroke="#10B981" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M19.09 14.441v4.44a2.37 2.37 0 0 1-2.369 2.369H5.12a2.37 2.37 0 0 1-2.369-2.383V7.279a2.356 2.356 0 0 1 2.37-2.37H9.56"/><path d="M6.835 15.803v-2.165c.002-.357.144-.7.395-.953l9.532-9.532a1.362 1.362 0 0 1 1.934 0l2.151 2.151a1.36 1.36 0 0 1 0 1.934l-9.532 9.532a1.361 1.361 0 0 1-.953.395H8.197a1.362 1.362 0 0 1-1.362-1.362M19.09 8.995l-4.085-4.086"/></g></svg>';
        echo '</button>';


        
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
    <button onclick="closeInvoice()" > <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 1024 1024" fill="#2563eb"><path fill="#2563eb" d="M512 0C229.232 0 0 229.232 0 512c0 282.784 229.232 512 512 512c282.784 0 512-229.216 512-512C1024 229.232 794.784 0 512 0zm0 961.008c-247.024 0-448-201.984-448-449.01c0-247.024 200.976-448 448-448s448 200.977 448 448s-200.976 449.01-448 449.01zm181.008-630.016c-12.496-12.496-32.752-12.496-45.248 0L512 466.752l-135.76-135.76c-12.496-12.496-32.752-12.496-45.264 0c-12.496 12.496-12.496 32.752 0 45.248L466.736 512l-135.76 135.76c-12.496 12.48-12.496 32.769 0 45.249c12.496 12.496 32.752 12.496 45.264 0L512 557.249l135.76 135.76c12.496 12.496 32.752 12.496 45.248 0c12.496-12.48 12.496-32.769 0-45.249L557.248 512l135.76-135.76c12.512-12.512 12.512-32.768 0-45.248z"/></svg> </button>
    
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
            <!-- ردیف جمع کل -->
            <tr class="bg-gray-100 font-bold">
              <td colspan="5" class="border px-2 py-2 text-right">جمع کل</td>
              <td class="border px-2 py-2">${price},000 تومان</td>
            </tr>
          </tbody>
        </table>

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




<!-- مودال مربوط به ویرایش -->



<!-- فرم ویرایش محصول -->
<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
        <h2 class="text-xl font-bold mb-4">ویرایش محصول</h2>
        <form id="editForm" method="POST" action="update_product.php">
            <input type="hidden" name="id" id="edit_id">

            <div class="mb-3">
                <label class="block mb-1">نام محصول</label>
                <input type="text" name="name" id="edit_name" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-3">
                <label class="block mb-1">سایز</label>
                <input type="text" name="size" id="edit_size" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-3">
                <label class="block mb-1">رنگ</label>
                <input type="text" name="color" id="edit_color" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-3">
                <label class="block mb-1">تاریخ</label>
                <input type="text" name="date" id="edit_date" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-3">
                <label class="block mb-1">قیمت</label>
                <input type="text" name="price" id="edit_price" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-3">
                <label class="block mb-1">روش پرداخت</label>
                <input type="text" name="payment_method" id="edit_payment_method" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded">انصراف</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">ذخیره تغییرات</button>
            </div>
        </form>
    </div>
</div>


<script>
function editProduct(id) {
    fetch("product_action.php?id=" + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById("edit_id").value = data.id;
            document.getElementById("edit_name").value = data.name;
            document.getElementById("edit_size").value = data.size;
            document.getElementById("edit_color").value = data.color;
            document.getElementById("edit_date").value = data.date;
            document.getElementById("edit_price").value = data.price;
            document.getElementById("edit_payment_method").value = data.payment_method;

            document.getElementById("editModal").classList.remove("hidden");
        });
}

function closeEditModal() {
    document.getElementById("editModal").classList.add("hidden");
}
</script>


<!-- Modal تایید حذف محصول -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-bold mb-4">تایید حذف محصول</h2>
        <p class="mb-4">آیا مطمئن هستید که می‌خواهید این محصول را حذف کنید؟</p>
        <div class="flex justify-end gap-2">
            <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">انصراف</button>
            <a href="#" id="deleteConfirmBtn" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">حذف</a>
        </div>
    </div>
</div>

<script>
function confirmDelete(productId) {
    // نمایش مودال
    const modal = document.getElementById("deleteModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    // تغییر لینک تایید به لینک حذف محصول
    const deleteBtn = document.getElementById("deleteConfirmBtn");
    deleteBtn.href = "delete_product.php?id=" + productId;
}

function closeDeleteModal() {
    const modal = document.getElementById("deleteModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}
</script>







</body>

</html>
