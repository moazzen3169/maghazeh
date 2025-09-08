<?php
// اتصال به دیتابیس
$host = "localhost";
$user = "root";
$pass = "";
$db = "salam";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$monthlySales = [];

// آرایه‌ای برای نگهداری داده‌ها بر اساس سال و ماه
$salesByYear = [];

// کوئری برای جمع‌آوری تعداد محصولات بر اساس سال و ماه
$sql = "
    SELECT 
        SUBSTRING(date, 1, 4) AS year,
        SUBSTRING(date, 6, 2) AS month,
        COUNT(*) AS total
    FROM products
    GROUP BY year, month
    ORDER BY year, month
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $year = $row['year'];
    $month = ltrim($row['month'], '0'); // حذف صفر ابتدایی
    $total = $row['total'];

    // آرایه سال و ماه
    if (!isset($salesByYear[$year])) {
        $salesByYear[$year] = array_fill(1, 0, 0); // 12 ماه
    }

    $salesByYear[$year][$month] = (int)$total;
}

// تبدیل به آرایه قابل استفاده در نمودار
foreach ($salesByYear as $year => $months) {
    $monthlySales[$year] = $months;
}

// $monthlySales حالا چیزی شبیه این است:
// [
//   "1403" => [1=>12,2=>5,3=>0,...,12=>8],
//   "1404" => [1=>20,2=>15,3=>25,...,12=>0]
// ]

// فروش هفتگی
$weeklySales = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i day"));
    $sql = "SELECT COUNT(*) as total FROM products WHERE date_added LIKE '$date%'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $weeklySales[] = $row['total'] ?? 0;
}

// فروش سالانه
$years = ['1403','1404','1405'];
$yearlySales = [];
foreach($years as $year){
    $sql = "SELECT COUNT(*) as total FROM products WHERE date LIKE '$year/%'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $yearlySales[] = $row['total'] ?? 0;
}

// پرفروش‌ترین رنگ‌ها
$colorSales = [];
$sql = "SELECT color, COUNT(*) as total FROM products GROUP BY color ORDER BY total DESC LIMIT 5";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
    $colorSales[$row['color']] = $row['total'];
}

// پرفروش‌ترین سایزها
$sizeSales = [];
$sql = "SELECT size, COUNT(*) as total 
        FROM products 
        WHERE size IS NOT NULL AND size <> '' 
        GROUP BY size 
        ORDER BY total DESC 
        LIMIT 5";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
    $sizeSales[$row['size']] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>داشبورد فروش</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>

* {
            font-family: peyda;
        }
        
    .sidebar {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      width: 260px;
      background: #fff;
      border-left: 1px solid #e5e7eb;
      overflow-y: auto;
      z-index: 50;
    }
    .main {
      margin-right: 260px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .chart-container {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      height: 400px; /* ارتفاع ثابت کارت */
      display: flex;
      flex-direction: column;
    }
    .chart-container canvas {
      flex: 1;
    }
  </style>
</head>
<body class="bg-gray-100">

  <!-- Sidebar -->
  <?php include("sidebar.php"); ?>

  <!-- Main Content -->
  <div class="main">
    <!-- Header -->
    <div >
      <?php include("header.php"); ?>
    </div>

    <!-- Charts -->
    <div class="p-6 space-y-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-4">آمار فروش</h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">


        <div class="chart-container">
          <h2 class="mb-3 font-semibold text-gray-700">فروش هفتگی</h2>
          <canvas id="weeklyChart"></canvas>
        </div>

        <div class="chart-container">
          <h2 class="mb-3 font-semibold text-gray-700">فروش سالانه</h2>
          <canvas id="yearlyChart"></canvas>
        </div>
        <div class="chart-container sm:col-span-2">
          <h2 class="mb-3 font-semibold text-gray-700">فروش ماهانه</h2>
          <canvas id="monthlyChart"></canvas>
        </div>

        <div class="chart-container">
          <h2 class="mb-3 font-semibold text-gray-700">پرفروش‌ترین رنگ‌ها</h2>
          <canvas id="colorChart"></canvas>
        </div>

        <div class="chart-container ">
          <h2 class="mb-3 font-semibold text-gray-700">پرفروش‌ترین سایزها</h2>
          <canvas id="sizeChart"></canvas>
        </div>

        <div class="chart-container  sm:col-span-2">
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

                        <div class="flex gap-4 mb-4 " style="direction:rtl; display: flex; align-items: center; ">
    <div class="flex items-center gap-1">
        <span class="w-4 h-4 bg-red-500 inline-block rounded-sm"></span>
        <span>تا 25,000,000</span>
    </div>
    <div class="flex items-center gap-1">
        <span class="w-4 h-4 bg-orange-500 inline-block rounded-sm"></span>
        <span>تا 50,000,000</span>
    </div>
    <div class="flex items-center gap-1">
        <span class="w-4 h-4 bg-blue-500 inline-block rounded-sm"></span>
        <span>تا 75,000,000</span>
    </div>
    <div class="flex items-center gap-1">
        <span class="w-4 h-4 bg-green-500 inline-block rounded-sm"></span>
        <span>بیش از 75,000,000</span>
    </div>
</div>


                        <div class="h-64">
                            <!-- Chart Canvas -->
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
        </div>
        



        








      </div>
    </div>
  </div>

  <?php
$year = '1404'; // سال موردنظر
$monthlyData = $monthlySales[$year] ?? array_fill(0, 12, 0); // اگر داده نبود صفر
?>
  <!-- Scripts -->
  <script>
new Chart(document.getElementById('monthlyChart'), {
  type: 'line',
  data: {
    datasets: [{
      label: 'تعداد فروش <?= $year ?>',
      data: <?= json_encode($monthlyData) ?>, // فقط مقادیر ماه‌ها
      borderColor: 'rgba(37, 99, 235, 1)',
      backgroundColor: 'rgba(37, 99, 235, 0.2)',
      fill: true,
      tension: 0.4
    }]
  },
  options: { responsive: true, maintainAspectRatio: false }
});

    // هفتگی
    new Chart(document.getElementById('weeklyChart'), {
      type: 'bar',
      data: {
        labels: ['6روز قبل','5روز قبل','4روز قبل','3روز قبل','2روز قبل','دیروز','امروز'],
        datasets: [{
          label: 'تعداد فروش',
          data: <?= json_encode($weeklySales) ?>,
          backgroundColor: 'rgba(99, 102, 241, 0.7)',
          borderColor: 'rgba(99, 102, 241, 1)',
          borderWidth: 1
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });

    // سالانه
    new Chart(document.getElementById('yearlyChart'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($years) ?>,
        datasets: [{
          label: 'تعداد فروش',
          data: <?= json_encode($yearlySales) ?>,
          backgroundColor: ['rgba(239, 68, 68, 0.7)','rgba(16, 185, 129, 0.7)','rgba(59, 130, 246, 0.7)'],
          borderColor: ['rgba(239, 68, 68, 1)','rgba(16, 185, 129, 1)','rgba(59, 130, 246, 1)'],
          borderWidth: 1
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });

    // رنگ‌ها
    new Chart(document.getElementById('colorChart'), {
      type: 'pie',
      data: {
        labels: <?= json_encode(array_keys($colorSales)) ?>,
        datasets: [{
          data: <?= json_encode(array_values($colorSales)) ?>,
          backgroundColor: [
            'rgba(239, 68, 68, 0.7)',
            'rgba(16, 185, 129, 0.7)',
            'rgba(59, 130, 246, 0.7)',
            'rgba(245, 158, 11, 0.7)',
            'rgba(139, 92, 246, 0.7)'
          ]
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });

    // سایزها
    new Chart(document.getElementById('sizeChart'), {
      type: 'doughnut',
      data: {
        labels: <?= json_encode(array_keys($sizeSales)) ?>,
        datasets: [{
          data: <?= json_encode(array_values($sizeSales)) ?>,
          backgroundColor: [
            'rgba(239, 68, 68, 0.7)',
            'rgba(16, 185, 129, 0.7)',
            'rgba(59, 130, 246, 0.7)',
            'rgba(245, 158, 11, 0.7)',
            'rgba(139, 92, 246, 0.7)'
          ]
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  </script>












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

    // آرایه رنگ‌ها بر اساس محدوده‌ها
    const backgroundColors = dataFromDB.map(value => {
        if (value <= 25000000) {
            return 'rgba(255, 0, 0, 0.71)';   // قرمز
        } else if (value <= 50000000) {
            return 'rgba(255, 128, 0, 0.84)';   // نارنجی
        } else if (value <= 75000000) {
            return 'rgba(0, 153, 255, 0.9)';   // آبی
        } else {
            return '#4DCBA1';   // سبز
        }
    });

    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelsFromDB,
            datasets: [{
                label: 'میزان فروش',
                data: dataFromDB,
                backgroundColor: backgroundColors, // استفاده از آرایه رنگ‌ها
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





</body>
</html>
