<?php
// اتصال به دیتابیس
try {
    $pdo = new PDO("mysql:host=localhost;dbname=salam;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("اتصال به دیتابیس ناموفق بود: " . $e->getMessage());
}

// دریافت پارامتر ماه از URL
$month = $_GET['month'] ?? '';
$month = htmlspecialchars($month);

// تقسیم ماه و سال
$monthParts = explode('/', $month);
$year = $monthParts[0] ?? '';
$monthNum = $monthParts[1] ?? '';

// دریافت فاکتورهای ماه انتخاب شده
$stmt = $pdo->prepare("
    SELECT * FROM factor 
    WHERE date LIKE :month_pattern
    ORDER BY returned ASC, date DESC
");
$stmt->execute([':month_pattern' => "$year/$monthNum/%"]);
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// محاسبه آمار کلی
$totalNormalProducts = 0;
$totalReturnedProducts = 0;
$totalNormalAmount = 0;
$totalReturnedAmount = 0;

foreach ($invoices as $invoice) {
    if ($invoice['returned'] == 1) {
        $totalReturnedProducts += $invoice['stock'];
        $totalReturnedAmount += $invoice['final_price'];
    } else {
        $totalNormalProducts += $invoice['stock'];
        $totalNormalAmount += $invoice['final_price'];
    }
}

$netAmount = $totalNormalAmount - $totalReturnedAmount;
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جزئیات فاکتورهای <?= $month ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        * {
            font-family: 'Vazirmatn', 'modam', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
        }

        body {
            color: #334155;
        }

        h1, h2, h3, h4 {
            font-weight: 700;
            line-height: 1.3;
        }

        @font-face {
            font-family: 'Vazirmatn';
            src: url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/fonts/webfonts/Vazirmatn-Regular.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        
        body {
            background-color: #f8fafc;
        }

        .container-65 {
            width: 80%;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* Header Styles */
        .header_section {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2.5rem;
            overflow: hidden;
            padding: 2rem;
            position: relative;
        }
        .header_section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.05)" d="M0,0 L100,0 L100,100 L0,100 Z"></path></svg>');
            opacity: 0.5;
        }

        /* Table Styles */
        .invoice-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .invoice-table th, 
        .invoice-table td {
            padding: 1.25rem;
            text-align: right;
            border-bottom: 1px solid #f1f5f9;
            font-size: 1rem;
        }

        .invoice-table th {
            background-color: #1d4ed8;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            position: sticky;
            top: 0;
        }

        .invoice-table tr:hover {
            background-color: #f8fafc;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .invoice-table tr:hover {
            background-color: #f8fafc;
        }

        /* Summary Cards */
        .summary-card {
            border-radius: 16px;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            border-top: 4px solid;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .summary-card:nth-child(1) {
            border-top-color: #10b981;
        }
        .summary-card:nth-child(2) {
            border-top-color: #ef4444;
        }
        .summary-card:nth-child(3) {
            border-top-color: #3b82f6;
        }

        .summary-card .icon-container {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
        }

        .summary-card h3 {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 1024px) {
            .container-65 {
                width: 85%;
            }
        }
        @media (max-width: 768px) {
            .container-65 {
                width: 95%;
            }
        }

        .header_sectiom{
            border: 1px solid #333;

        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
            padding: 1rem 0;
        }

        .action-buttons button, 
        .action-buttons a {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .action-buttons button:hover, 
        .action-buttons a:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        @media print {
            .no-print, .action-buttons {
                display: none !important;
            }
        }

        .btns{
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        #save-as-image{
            background-color: #1D4ED8;
        }

    </style>
</head>
<body class="bg-gray-50">
    <div class="container-65 mx-auto px-4 py-8" id="invoice-container">
        <!-- هدر صفحه -->
        <div class="header_section">
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">فروشگاه هادی</h1>
                        <p class="text-blue-100 text-lg md:text-xl">جزئیات فاکتورهای ماه <?= $month ?></p>
                    </div>
                    <div id="current-date" class="bg-white bg-opacity-20 backdrop-blur-sm text-white px-6 py-3 rounded-lg text-lg flex items-center gap-2">
                        <i class="far fa-calendar-alt"></i>
                        <span>در حال بارگذاری تاریخ...</span>
                    </div>
                </div>
            </div>
        </div>


        <!-- جدول فاکتورها -->
        <div class="bg-white  overflow-hidden mb-8">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>نام محصول</th>
                        <th>قیمت واحد</th>
                        <th>تعداد</th>
                        <th>مبلغ کل</th>
                        <th>تاریخ</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr class="<?= $invoice['returned'] == 1 ? 'returned-row' : 'normal-row' ?>">
                            <td><?= htmlspecialchars($invoice['name']) ?></td>
                            <td><?= number_format($invoice['price'], 0) ?> تومان</td>
                            <td><?= $invoice['stock'] ?></td>
                            <td><?= number_format($invoice['final_price'], 0) ?> تومان</td>
                            <td><?= htmlspecialchars($invoice['date']) ?></td>
                            <td>
                                <?php if ($invoice['returned'] == 1): ?>
                                    <span class="text-red-500 font-medium">مرجوعی</span>
                                <?php else: ?>
                                    <span class="text-green-500 font-medium">خرید</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">هیچ فاکتوری برای این ماه ثبت نشده است</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- جمع‌بندی فاکتورها -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- محصولات عادی -->
            <div class="summary-card">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-3">
                            <i class="fas fa-shopping-cart text-lg"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">محصولات خریداری شده</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">تعداد کل:</span>
                            <span class="font-medium text-lg"><?= $totalNormalProducts ?> عدد</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">جمع مبلغ:</span>
                            <span class="font-medium text-green-600 text-lg"><?= number_format($totalNormalAmount, 0) ?> تومان</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- محصولات مرجوعی -->
            <div class="summary-card">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-3">
                            <i class="fas fa-undo text-lg"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">محصولات مرجوعی</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">تعداد کل:</span>
                            <span class="font-medium text-lg"><?= $totalReturnedProducts ?> عدد</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">جمع مبلغ:</span>
                            <span class="font-medium text-red-600 text-lg"><?= number_format($totalReturnedAmount, 0) ?> تومان</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جمع کل -->
            <div class="summary-card">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-3">
                            <i class="fas fa-chart-line text-lg"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">جمع‌بندی نهایی</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">تعداد کل ردیف ها:</span>
                            <span class="font-medium text-lg"><?= count($invoices) ?> مورد</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">خالص فروش:</span>
                            <span class="font-medium text-blue-600 text-lg"><?= number_format($netAmount, 0) ?> تومان</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            
        <!-- دکمه‌های اقدام -->
        <div class="flex justify-center md:justify-end gap-4 mb-8 print:hidden">
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                <i class="fas fa-print ml-2"></i>
                چاپ فاکتور
            </button>
            <button id="save-as-image" class="bg-emerald-600 hover:bg-emerald-700 text-white py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                <i class="fas fa-camera ml-2"></i>
                ذخیره به عنوان عکس
            </button>
            <a href="factor.php" class="bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left ml-2"></i>
                بازگشت
            </a>
        </div>

    </div>

    

    <script>
        // نمایش تاریخ جاری
        fetch('https://api.keybit.ir/time/')
            .then(response => response.json())
            .then(data => {
                const dateText = data.date.full.official.usual.fa;
                document.getElementById('current-date').innerText = `تاریخ امروز: ${dateText}`;
            })
            .catch(error => {
                console.error('خطا در دریافت تاریخ:', error);
                document.getElementById('current-date').innerText = 'امروز: ' + new Date().toLocaleDateString('fa-IR');
            });

        // ذخیره به عنوان عکس
        document.getElementById('save-as-image').addEventListener('click', function() {
            const element = document.getElementById('invoice-container');
            
            // مخفی کردن دکمه‌ها قبل از عکسبرداری
            const buttons = document.querySelectorAll('.print-hidden');
            buttons.forEach(btn => btn.style.display = 'none');
            
            html2canvas(element, {
                scale: 2, // کیفیت بالاتر
                logging: false,
                useCORS: true,
                allowTaint: true
            }).then(canvas => {
                // نمایش دوباره دکمه‌ها
                buttons.forEach(btn => btn.style.display = '');
                
                // ایجاد لینک دانلود
                const link = document.createElement('a');
                link.download = `فاکتور-ماه-<?= $month ?>-${new Date().toLocaleDateString('fa-IR')}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        });
         // تغییر در اسکریپت ذخیره عکس برای مخفی کردن دکمه‌ها
         document.getElementById('save-as-image').addEventListener('click', function() {
            const element = document.getElementById('invoice-container');
            const buttons = document.querySelector('.action-buttons');
            
            // مخفی کردن دکمه‌ها قبل از عکسبرداری
            buttons.style.visibility = 'hidden';
            
            html2canvas(element, {
                scale: 2,
                logging: false,
                useCORS: true,
                allowTaint: true
            }).then(canvas => {
                // نمایش دوباره دکمه‌ها
                buttons.style.visibility = 'visible';
                
                // ایجاد لینک دانلود
                const link = document.createElement('a');
                link.download = `فاکتور-ماه-<?= $month ?>-${new Date().toLocaleDateString('fa-IR')}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        });

        // تنظیمات چاپ
        window.addEventListener('beforeprint', () => {
            document.querySelectorAll('.print-hidden').forEach(el => {
                el.style.display = 'none';
            });
        });

        window.addEventListener('afterprint', () => {
            document.querySelectorAll('.print-hidden').forEach(el => {
                el.style.display = '';
            });
        });
    </script>
</body>
</html>