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

// فروش ماهانه
$monthlySales = [];
for ($i = 0; $i < 12; $i++) {
    $month = sprintf("%02d", $i + 1);
    $sql = "SELECT COUNT(*) as total FROM products WHERE date LIKE '1404/$month/%'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $monthlySales[] = $row['total'] ?? 0;
}

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
          <h2 class="mb-3 font-semibold text-gray-700">فروش ماهانه</h2>
          <canvas id="monthlyChart"></canvas>
        </div>

        <div class="chart-container">
          <h2 class="mb-3 font-semibold text-gray-700">فروش هفتگی</h2>
          <canvas id="weeklyChart"></canvas>
        </div>

        <div class="chart-container">
          <h2 class="mb-3 font-semibold text-gray-700">فروش سالانه</h2>
          <canvas id="yearlyChart"></canvas>
        </div>

        <div class="chart-container">
          <h2 class="mb-3 font-semibold text-gray-700">پرفروش‌ترین رنگ‌ها</h2>
          <canvas id="colorChart"></canvas>
        </div>

        <div class="chart-container sm:col-span-2">
          <h2 class="mb-3 font-semibold text-gray-700">پرفروش‌ترین سایزها</h2>
          <canvas id="sizeChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // ماهانه
    new Chart(document.getElementById('monthlyChart'), {
      type: 'line',
      data: {
        labels: ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'],
        datasets: [{
          label: 'تعداد فروش',
          data: <?= json_encode($monthlySales) ?>,
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
        labels: ['شنبه','یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنجشنبه','جمعه'],
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
</body>
</html>
