<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه هادی - مدیریت پرداخت‌ها</title>
    <script src="tailwind.js"></script>
    <link href="css-library.css" rel="stylesheet">
    <style>
        /* Same styling as dashboard.php */
        :root {
            /* رنگ‌های تم روشن (Light Theme) */
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
            font-family: peyda;
            background-color: var(--color-bg);
        }
        .sidebar {
    transition: all 0.3s ease;
}

.sidebar-item:hover {
    background-color: var(--color-hover-bg);
}.product-table{
    text-align: center;
}



.submit-btn{
    background-color: #2563eb;
}

.submit-btn:hover{
    background-color:rgb(36, 86, 193);
}

.show-detail{
    background: linear-gradient(90deg, #3B82F6 0%, #2563eb 100%);
}

    </style>
</head>

<body class="bg-gray-50">
    <!-- Same sidebar and header structure as dashboard -->
    <div class="flex h-screen overflow-hidden">
        <?php include("sidebar.php");?>



        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Navigation -->
            <?php include("header.php");?>




        <!-- Payment Summary by Destination -->
<div class="glass-card p-6 rounded-xl mb-6">
    <h3 class="font-semibold text-gray-800 mb-6 text-lg border-b pb-3">خلاصه پرداختی‌ها براساس مقصد</h3>
    <?php
    $conn = new mysqli("localhost", "root", "", "salam");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get payment totals by destination
    $destinationsResult = $conn->query("
        SELECT 
            destination,
            COUNT(*) as payment_count,
            SUM(amount) as total_amount
        FROM payments 
        GROUP BY destination
        ORDER BY total_amount DESC
    ");

    $destinations = [];
    while ($row = $destinationsResult->fetch_assoc()) {
        $destinations[] = $row;
    }

    // Get overall totals
    $overallResult = $conn->query("
        SELECT 
            COUNT(*) as total_count,
            SUM(amount) as overall_amount
        FROM payments
    ");
    $overall = $overallResult->fetch_assoc();
    $conn->close();
    ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-gray-500 text-sm">تعداد کل مقاصد</h4>
                    <p class="text-xl font-bold mt-1"><?= count($destinations) ?></p>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl">
                    <i class="fas fa-list text-blue-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-gray-500 text-sm">تعداد کل پرداخت‌ها</h4>
                    <p class="text-xl font-bold mt-1"><?= $overall['total_count'] ?></p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl">
                    <i class="fas fa-money-bill-wave text-green-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-gray-500 text-sm">جمع کل مبالغ</h4>
                    <p class="text-xl font-bold mt-1"><?= number_format($overall['overall_amount']) ?> تومان</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-xl">
                    <i class="fas fa-coins text-purple-500"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-gray-500 border-b border-gray-200">
                    <th class="pb-2 text-right">مقصد پرداخت</th>
                    <th class="pb-2 text-center">تعداد پرداخت</th>
                    <th class="pb-2 text-center">جمع مبالغ</th>
                    <th class="pb-2 text-center">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($destinations as $destination): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50 ">
                    <td class="py-3 text-right"><?= htmlspecialchars($destination['destination']) ?></td>
                    <td class="py-3 text-center"><?= $destination['payment_count'] ?></td>
                    <td class="py-3 text-center"><?= number_format($destination['total_amount']) ?> تومان</td>
                    <td class="py-3 text-center">
                        <a href="pay.php?search=<?= urlencode($destination['destination']) ?>" 
                           class="show-detail" 
                           title="مشاهده جزئیات">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb"><g fill="none" stroke="#2563eb" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M3.587 13.779c1.78 1.769 4.883 4.22 8.413 4.22c3.53 0 6.634-2.451 8.413-4.22c.47-.467.705-.7.854-1.159c.107-.327.107-.913 0-1.24c-.15-.458-.385-.692-.854-1.159C18.633 8.452 15.531 6 12 6c-3.53 0-6.634 2.452-8.413 4.221c-.47.467-.705.7-.854 1.159c-.107.327-.107.913 0 1.24c.15.458.384.692.854 1.159Z"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0Z"/></g></svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>




            <?php
            // اتصال به دیتابیس
            $pdo = new PDO("mysql:host=localhost;dbname=salam;charset=utf8", "root", "");

            // دریافت تاریخ‌های پرداخت منحصر به فرد، تعداد و مجموع مبالغ
            $paymentDates = $pdo->query("
    SELECT 
        DATE_FORMAT(payment_date, '%Y/%m') as formatted_date,
        COUNT(*) as payment_count,
        SUM(amount) as total_amount
    FROM payments 
    GROUP BY formatted_date
    ORDER BY formatted_date DESC
")->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- نمایش لیست مقاصد پرداختی -->
            <div class="glass-card p-6  ">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <?php foreach ($paymentDates as $date): ?>
                        <div
                            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-5 border border-gray-200 flex flex-col h-full min-h-[180px]">
                            <div class="flex-grow">
                                <div class="flex items-center justify-between mb-4 gap-2">
                                    <h4 class="font-bold text-gray-800 text-lg">
                                        <?= convertToPersianNumbers($date['formatted_date']) ?>
                                    </h4>
                                    <div class="bg-green-50 p-3 rounded-xl mr-3">
                                        <i class="fas fa-calendar-day text-green-600 text-lg"></i>
                                    </div>
                                </div>

                                <div class="mt-4 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">تعداد پرداخت:</span>
                                        <span class="font-medium"><?= $date['payment_count'] ?></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">جمع مبالغ:</span>
                                        <span class="font-medium"><?= number_format($date['total_amount']) ?> تومان</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto pt-4">
                                <a href="pay.php?search=<?= urlencode($date['formatted_date']) ?>"
                                    class="w-full block text-center bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 rounded-lg transition-colors duration-200">
                                    مشاهده جزئیات
                                    <i class="fas fa-chevron-left mr-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php
                // تابع تبدیل اعداد انگلیسی به فارسی
                function convertToPersianNumbers($string)
                {
                    $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
                    return str_replace($english, $persian, $string);
                }
                ?>
            </div>

            <!-- Payment Content -->
            <main class="p-6">
                <!-- Payment Form -->
                <div class="glass-card p-6 rounded-xl mb-6">
                    <h3 class="font-semibold text-gray-800 mb-6 text-lg border-b pb-3">ثبت پرداخت جدید</h3>
                    <form method="post" action="add_payment.php" class="grid gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- مقصد پرداخت -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">مقصد پرداخت</label>
                                <input type="text" name="destination"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500"
                                    placeholder="مثلا بهزاد" required>
                            </div>

                            <!-- مبلغ پرداخت -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">مبلغ پرداخت (تومان)</label>
                                <input type="number" name="amount"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500"
                                    placeholder="5,000,000" required>
                            </div>

                            <!-- تاریخ پرداخت -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">تاریخ پرداخت</label>
                                <input type="text" name="payment_date" id="date-input"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <!-- دلیل پرداخت -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">دلیل پرداخت</label>
                                <input type="text" name="reason"
                                    class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500"
                                    placeholder="بدهی" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="grid grid-cols-1">
                            <button type="submit"
                                class="w-full md:w-3/3 mx-auto submit-btn  text-white py-2.5 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                
                                <span>ثبت پرداخت</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment List -->
                <div class="glass-card p-6 rounded-xl">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-lg">لیست پرداخت‌ها</h3>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle ml-1"></i>
                                <?php
                                $conn = new mysqli("localhost", "root", "", "salam");

                                // Total payments count
                                $totalSql = "SELECT COUNT(*) as total, SUM(amount) as total_amount FROM payments";
                                $totalResult = $conn->query($totalSql);
                                $totalRow = $totalResult->fetch_assoc();
                                $totalPayments = $totalRow['total'];
                                $totalAmount = $totalRow['total_amount'] ?? 0;

                                // Search results count and sum
                                $searchCount = 0;
                                $searchSum = 0;

                                if (isset($_GET['search'])) {
                                    $searchTerm = $_GET['search'];

                                    // اگر تاریخ به فرمت ۱۴۰۴/۰۳ وارد شده باشد
                                    if (preg_match('/^[0-9]{4}\/[0-9]{2}$/', $searchTerm)) {
                                        // تبدیل فرمت تاریخ از 1404/03 به 1404-03
                                        $persianDate = str_replace('/', '-', $searchTerm);
                                        $countSql = "SELECT COUNT(*) as count, SUM(amount) as sum FROM payments 
                    WHERE destination LIKE '%$searchTerm%' 
                    OR payment_date LIKE '$persianDate%'";
                                    } else {
                                        // برای جستجوی معمولی
                                        $countSql = "SELECT COUNT(*) as count, SUM(amount) as sum FROM payments 
                    WHERE destination LIKE '%$searchTerm%' 
                    OR payment_date LIKE '%$searchTerm%'";
                                    }

                                    $countResult = $conn->query($countSql);
                                    $countRow = $countResult->fetch_assoc();
                                    $searchCount = $countRow['count'];
                                    $searchSum = $countRow['sum'] ? $countRow['sum'] : 0;
                                }

                                echo '<span>تعداد کل پرداخت‌ها: ' . $totalPayments . ' - مجموع مبالغ: ' . number_format($totalAmount) . ' تومان</span>';

                                if (isset($_GET['search']) && $searchCount > 0) {
                                    echo '<span class="mx-2">|</span>';
                                    echo '<span class="text-blue-500">تعداد نتایج جستجو: ' . $searchCount . '</span>';
                                    echo '<span class="mx-2">|</span>';
                                    echo '<span class="text-green-500">جمع مبالغ: ' . number_format($searchSum) . ' تومان</span>';
                                }

                                $conn->close();
                                ?>
                            </div>
                        </div>
                        <form method="GET" class="w-full md:w-auto mt-4 md:mt-0">
                            <div class="relative">
                                <input type="text" name="search" placeholder="جستجو..."
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                    class="bg-gray-100 border-0 rounded-lg px-4 py-2 pr-10 w-full md:w-64 focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="absolute left-3 top-2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full product-table">
                            <thead>
                                <tr class="text-gray-500 border-b border-gray-200">
                                    <th class="pb-2 text-right">مقصد</th>
                                    <th class="pb-2 text-center">مبلغ</th>
                                    <th class="pb-2 text-center">تاریخ پرداخت</th>
                                    <th class="pb-2 text-center">دلیل</th>
                                    <th class="pb-2 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $conn = new mysqli("localhost", "root", "", "salam");

                                if (isset($_GET['search'])) {
                                    $searchTerm = $_GET['search'];

                                    // اگر تاریخ به فرمت ۱۴۰۴/۰۳ وارد شده باشد
                                    if (preg_match('/^[0-9]{4}\/[0-9]{2}$/', $searchTerm)) {
                                        // تبدیل فرمت تاریخ از 1404/03 به 1404-03
                                        $persianDate = str_replace('/', '-', $searchTerm);
                                        $sql = "SELECT * FROM payments 
                                                WHERE destination LIKE '%$searchTerm%' 
                                                OR payment_date LIKE '$persianDate%' 
                                                ORDER BY id DESC";
                                    } else {
                                        // برای جستجوی معمولی
                                        $sql = "SELECT * FROM payments 
                                                WHERE destination LIKE '%$searchTerm%' 
                                                OR payment_date LIKE '%$searchTerm%' 
                                                ORDER BY id DESC";
                                    }
                                } else {
                                    $sql = "SELECT * FROM payments ORDER BY id DESC";
                                }
                            
                                $result = $conn->query($sql);
                            
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr class="border-b border-gray-100 hover:bg-gray-50">';
                                        echo '<td class="py-3 text-right">' . htmlspecialchars($row['destination']) . '</td>';
                                        echo '<td class="py-3 text-center">' . number_format($row['amount']) . ' تومان</td>';
                                        echo '<td class="py-3 text-center">' . htmlspecialchars($row['payment_date']) . '</td>';
                                        echo '<td class="py-3 text-center">' . htmlspecialchars($row['reason']) . '</td>';
                                        echo '<td class="py-3 text-center">';
                                        echo '<a href="delete_payment.php?id=' . $row['id'] . '" class="text-red-500 hover:text-red-700 transition duration-200" title="حذف">';
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#EF4444"><path fill="none" stroke="#EF4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/></svg>';
                                        echo '</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="py-4 text-center text-gray-500">پرداختی یافت نشد</td></tr>';
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

    <!-- Same scripts as dashboard -->
    <script>
        // Mobile menu toggle
        document.querySelector('.md\\:hidden').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });
    </script>

    <script>
        fetch('https://api.keybit.ir/time/')
            .then(response => response.json())
            .then(data => {
                const dateText = data.date.full.official.usual.fa;  // مسیر دقیق تاریخ
                document.getElementById('date-container').innerText = `تاریخ امروز :  ${dateText}`;
            })
            .catch(error => {
                console.error('خطا در دریافت تاریخ:', error);
                document.getElementById('date-container').innerText = 'خطا در دریافت تاریخ';
            });
    </script>
    <script>
        fetch('https://api.keybit.ir/time/')
            .then(response => response.json())
            .then(data => {
                const todayDate = data.date.full.unofficial.usual.en;
                document.getElementById('date-input').value = todayDate;
            })
            .catch(error => {
                console.error('خطا در دریافت تاریخ:', error);
            });
    </script>
    <script src="scripts.js"></script>

</body>

</html>