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
            font-family: peyda;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container-65 {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header_section {
            background: #FEFEFE;
            color: gray;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            border-radius:10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Table Styles */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th, 
        .invoice-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .invoice-table th {
            background-color: #555;
            color: white;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .invoice-table tr:hover {
            background-color: #e9e9e9;
        }

        /* Summary Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-table th, 
        .summary-table td {
            padding: 12px;
            text-align: right;
            border: 1px solid #ddd;
        }

        .summary-table th {
            background-color: #555;
            color: white;
        }

        .summary-table tr:last-child {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .action-buttons button, 
        .action-buttons a {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        #save-as-image {
            background-color: #4CAF50;
            color: white;
        }

        @media print {
            .no-print, .action-buttons {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .container-65 {
                padding: 10px;
            }
            
            .invoice-table th, 
            .invoice-table td {
                padding: 8px;
                font-size: 14px;
            }
        }

        .negative {
    color: red;
    font-weight: bold;
}


    </style>
</head>
<body class="bg-gray-50">
    <div class="container-65 mx-auto px-4 py-8" id="invoice-container">
        <!-- هدر صفحه -->
        <div class="header_section">
            <h1>فروشگاه هادی</h1>
            <p>جزئیات فاکتورهای ماه <?= $month ?></p>
            <div id="current-date">
                <i class="far fa-calendar-alt"></i>
                <span>در حال بارگذاری تاریخ...</span>
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

        <!-- جدول جمع‌بندی -->
        <table class="summary-table">
    <thead>
        <tr>
            <th>نوع</th>
            <th>تعداد کل</th>
            <th>جمع مبلغ</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>محصولات خریداری شده</td>
            <td><?= $totalNormalProducts ?> عدد</td>
            <td class="<?= $totalNormalAmount < 0 ? 'negative' : '' ?>">
                <?= number_format($totalNormalAmount, 0) ?> تومان
            </td>
        </tr>
        <tr>
            <td>محصولات مرجوعی</td>
            <td class="<?= $totalReturnedProducts < 0 ? 'negative' : '' ?>">
                <?= $totalReturnedProducts ?> عدد
            </td>
            <td class="<?= $totalReturnedAmount < 0 ? 'negative' : '' ?>">
                <?= number_format($totalReturnedAmount, 0) ?> تومان
            </td>
        </tr>
        <tr class="total-row">
            <td>جمع نهایی</td>
            <td class="<?= count($invoices) < 0 ? 'negative' : '' ?>">
                <?= count($invoices) ?> مورد
            </td>
            <td class="<?= $netAmount < 0 ? 'negative' : '' ?>">
                <?= number_format($netAmount, 0) ?> تومان
            </td>
        </tr>
    </tbody>
</table>

            
        <!-- دکمه‌های اقدام -->
        <div class="action-buttons">
            <button onclick="window.print()">
                <i class="fas fa-print"></i>
                چاپ فاکتور
            </button>
            <button id="save-as-image">
                <i class="fas fa-camera"></i>
                ذخیره به عنوان عکس
            </button>
            <a href="factor.php">
                <i class="fas fa-arrow-left"></i>
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