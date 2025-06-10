<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه هادی - مدیریت فاکتورها</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="color.css">
    <style>


        body {
            font-family: modam;
            background-color: var(--color-bg);
        }

        .glass-card {
            background: var(--color-card-bg);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px var(--color-shadow);
            margin: 20px;
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
        
        .month-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(245,245,245,0.9) 100%);
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .month-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .month-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #3B82F6 0%, #6366F1 100%);
        }
        
        .month-card.returned-highlight::before {
            background: linear-gradient(90deg, #EF4444 0%, #F97316 100%);
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .stat-item:last-child {
            border-bottom: none;
        }
        
        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            flex-shrink: 0;
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


            <!-- فرم ثبت فاکتور جدید -->
            <div class="glass-card p-6 rounded-xl mb-6 mx-4">
                <h3 class="font-semibold text-gray-800 mb-6 text-lg border-b pb-3">ثبت فاکتور جدید</h3>
                <form method="post" action="factor_add.php" class="grid gap-6" dir="rtl">
                    <input type="hidden" name="redirect" value="<?= $_SERVER['REQUEST_URI'] ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <!-- نام محصول -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">نام محصول</label>
                            <input type="text" name="name" placeholder="نام محصول" required
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- قیمت واحد -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">قیمت واحد</label>
                            <input type="number" name="price" placeholder="قیمت واحد" required
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- تعداد -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">تعداد</label>
                            <input type="number" name="stock" placeholder="تعداد" required
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- تاریخ -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">تاریخ</label>
                            <input type="text" name="date" id="date-input" placeholder="مثلاً 1404/03/01" required
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- مرجوعی -->
                        <div class="space-y-2 flex items-end">
                            <label class="inline-flex items-center mt-1">
                                <input type="checkbox" name="returned" class="form-checkbox h-5 w-5 text-blue-600">
                                <span class="mr-2 text-gray-700">مرجوعی است؟</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1">
                        <button type="submit" name="add"
                            class="w-full md:w-3/3 mx-auto bg-blue-500 hover:bg-blue-600 text-white py-2.5 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-plus ml-2"></i>
                            <span>ثبت فاکتور</span>
                        </button>
                    </div>
                </form>
            </div>
<!-- Monthly Reports - Fixed Version -->
<div class="glass-card p-6 rounded-xl mb-6 mx-4">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-semibold text-gray-800 text-lg">گزارش ماهانه فاکتورها</h3>
        <div class="flex items-center text-sm text-gray-500">
            <i class="fas fa-info-circle ml-1"></i>
            <span>گزارش مالی بر اساس ماه‌های شمسی</span>
        </div>
    </div>

    <?php
    // 1. اتصال به دیتابیس با خطایابی
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=salam;charset=utf8mb4", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        echo "<!-- Connected to database successfully -->";
        
        // 2. اجرای کوئری با خطایابی
        $sql = "
            SELECT 
                SUBSTRING_INDEX(date, '/', 2) AS month_year,
                COUNT(*) AS total_orders,
                SUM(final_price) AS total_amount,
                SUM(CASE WHEN returned = 1 THEN 1 ELSE 0 END) AS returned_count,
                SUM(CASE WHEN returned = 0 THEN stock ELSE 0 END) AS total_products,
                SUM(CASE WHEN returned = 1 THEN stock ELSE 0 END) AS returned_products
            FROM factor
            GROUP BY month_year
            ORDER BY month_year DESC
        ";
        
        echo "<!-- SQL Query: " . htmlspecialchars($sql) . " -->";
        
        $stmt = $pdo->query($sql);
        $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<!-- Query executed successfully -->";
        echo "<!-- Number of records: " . count($monthlyData) . " -->";
        
    } catch (PDOException $e) {
        echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">';
        echo '<div class="flex items-center">';
        echo '<div class="flex-shrink-0 text-red-500">';
        echo '<i class="fas fa-exclamation-circle"></i>';
        echo '</div>';
        echo '<div class="ml-3">';
        echo '<p class="text-sm text-red-700">';
        echo 'خطا در ارتباط با پایگاه داده:<br>';
        echo htmlspecialchars($e->getMessage());
        echo '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        $monthlyData = [];
    }
    ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($monthlyData)): ?>
            <?php foreach ($monthlyData as $month): 
                $returnRate = ($month['total_orders'] > 0) ? 
                    round(($month['returned_count'] / $month['total_orders']) * 100, 1) : 0;
            ?>
                <div class="border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <!-- عنوان ماه -->
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h4 class="font-bold text-lg text-gray-800 flex items-center">
                                    <i class="far fa-calendar-alt ml-2 text-gray-500"></i>
                                    <?= htmlspecialchars($month['month_year'] ?? 'N/A') ?>
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?= htmlspecialchars($month['total_orders'] ?? 0) ?> فاکتور ثبت شده
                                </p>
                            </div>
                            <div class="bg-gray-100 text-gray-600 rounded-full w-9 h-9 flex items-center justify-center">
                                <i class="fas fa-file-invoice text-sm"></i>
                            </div>
                        </div>

                        <!-- اطلاعات مالی -->
                        <div class="space-y-3 mt-5">
                            <!-- مجموع کل -->
                            <div class="flex items-center">
                                <div class="bg-gray-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-wallet text-gray-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-600">مجموع درآمد</p>
                                </div>
                                <span class="font-medium text-gray-800">
                                    <?= number_format($month['total_amount'] ?? 0, 0) ?> تومان
                                </span>
                            </div>

                            <!-- سایر آیتم‌های اطلاعاتی به همین شکل -->
                            
                        </div>

                        <!-- دکمه مشاهده جزییات -->
                        <div class="mt-6">
                            <a href="factor_details.php?month=<?= urlencode($month['month_year'] ?? '') ?>"
                                class="block text-center bg-gray-800 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition duration-200 text-sm w-full">
                                <i class="fas fa-eye ml-2"></i>
                                مشاهده جزییات ماه
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center py-8 text-gray-500">
                <i class="fas fa-database text-2xl mb-2"></i>
                <p>هیچ داده‌ای برای نمایش وجود ندارد</p>
                <p class="text-xs mt-2">جدول factor ممکن است خالی باشد یا مشکلی در کوئری وجود دارد</p>
            </div>
        <?php endif; ?>
    </div>
</div>

            <!-- Content -->
            <main class="p-6 mx-4">
                <?php
                // اتصال به دیتابیس
                $pdo = new PDO("mysql:host=localhost;dbname=salam;charset=utf8", "root", "");

                // افزودن محصول
                if (isset($_POST['add'])) {
                    $name = $_POST['name'];
                    $price = $_POST['price'];
                    $stock = $_POST['stock'];
                    $date = $_POST['date'];
                    $returned = isset($_POST['returned']) ? 1 : 0;
                    $final_price = $price * $stock;

                    $stmt = $pdo->prepare("INSERT INTO factor (name, price, stock, final_price, date, returned) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $price, $stock, $final_price, $date, $returned]);
                    header("Location: factor.php");
                }

                // حذف محصول
                if (isset($_GET['delete'])) {
                    $id = $_GET['delete'];
                    $pdo->query("DELETE FROM factor WHERE id = $id");
                    header("Location: factor.php");
                }

                // ویرایش محصول
                if (isset($_POST['edit'])) {
                    $id = $_POST['id'];
                    $name = $_POST['name'];
                    $price = $_POST['price'];
                    $stock = $_POST['stock'];
                    $date = $_POST['date'];
                    $returned = isset($_POST['returned']) ? 1 : 0;
                    $final_price = $price * $stock;

                    $stmt = $pdo->prepare("UPDATE factor SET name=?, price=?, stock=?, final_price=?, date=?, returned=? WHERE id=?");
                    $stmt->execute([$name, $price, $stock, $final_price, $date, $returned, $id]);
                    header("Location: factor.php");
                }

                // دریافت داده‌ها
                $data = $pdo->query("SELECT * FROM factor ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <!-- Products List -->
                <div class="glass-card p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-lg">لیست فاکتورها</h3>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle ml-1"></i>
                                <span>تعداد کل فاکتورها: <?= count($data) ?></span>
                                <?php
                                $totalSum = array_reduce($data, function ($carry, $item) {
                                    return $carry + $item['final_price'];
                                }, 0);
                                ?>
                                <span class="mx-2">|</span>
                                <span class="text-green-500">جمع کل: <?= number_format($totalSum, 0, '.', ',') ?>
                                    تومان</span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full product-table text-right" dir="rtl">
                            <colgroup>
                                <col style="width: 25%">
                                <col style="width: 12%">
                                <col style="width: 10%">
                                <col style="width: 15%">
                                <col style="width: 15%">
                                <col style="width: 10%">
                                <col style="width: 13%">
                            </colgroup>
                            <thead>
                                <tr class="text-gray-500 border-b border-gray-200">
                                    <th class="pb-2 text-right">نام محصول</th>
                                    <th class="pb-2 text-center">قیمت واحد</th>
                                    <th class="pb-2 text-center">تعداد</th>
                                    <th class="pb-2 text-center">مبلغ نهایی</th>
                                    <th class="pb-2 text-center">تاریخ</th>
                                    <th class="pb-2 text-center">وضعیت</th>
                                    <th class="pb-2 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </td>
                                        <td class="py-3 text-center">
                                            <?= number_format($row['price'], 0, '.', ',') ?> تومان
                                        </td>
                                        <td class="py-3 text-center">
                                            <?= $row['stock'] ?>
                                        </td>
                                        <td class="py-3 text-center">
                                            <?= number_format($row['final_price'], 0, '.', ',') ?> تومان
                                        </td>
                                        <td class="py-3 text-center">
                                            <?= htmlspecialchars($row['date']) ?>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="<?= $row['returned'] ? 'text-red-500' : 'text-green-500' ?>">
                                                <?= $row['returned'] ? 'مرجوعی' : 'عادی' ?>
                                            </span>
                                        </td>
                                        <td class="py-3 text-center space-x-2">
                                            <form method="post" action="factor_delete.php" class="inline-block"
                                                onsubmit="return confirm('آیا از حذف این فاکتور مطمئن هستید؟')">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="redirect"
                                                    value="<?= urlencode($_SERVER['REQUEST_URI']) ?>">
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg transition duration-200">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($data)): ?>
                                    <tr>
                                        <td colspan="7" class="py-4 text-center text-gray-500">هیچ فاکتوری ثبت نشده است</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.querySelector('.md\\:hidden').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });

        // Form input animations
        const formInputs = document.querySelectorAll('.form-input');
        formInputs.forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.querySelector('label').classList.add('text-blue-500');
            });
            input.addEventListener('blur', function () {
                if (!this.value) {
                    this.parentElement.querySelector('label').classList.remove('text-blue-500');
                }
            });
        });
    </script>

    <script>
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
</body>

</html>