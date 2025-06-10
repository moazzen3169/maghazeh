<?php
header('Content-Type: application/json');

// اتصال به دیتابیس (مقادیر را با اطلاعات خود جایگزین کنید)
$host = 'localhost';
$dbname = 'salam';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// تنظیم منطقه زمانی به تهران
date_default_timezone_set('Asia/Tehran');

// تاریخ شروع و پایان برای سال 1404
$startDate = '2025-03-21'; // شروع فروردین 1404
$endDate = '2026-03-20';   // پایان اسفند 1404

// ایجاد آرایه‌ای از تمام ماه‌های سال 1404
$persianMonths = [
    'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
    'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
];

$result = [
    'labels' => [],
    'amounts' => array_fill(0, 12, 0)
];

// پر کردن لیبل‌ها با نام ماه‌های فارسی
foreach ($persianMonths as $month) {
    $result['labels'][] = $month . ' ۱۴۰۴';
}

// کوئری برای دریافت مجموع پرداخت‌ها در هر ماه از سال 1404
$query = "
    SELECT 
        MONTH(payment_date) - 3 AS month_index,
        SUM(amount) AS total_amount
    FROM payments
    WHERE payment_date >= :startDate AND payment_date <= :endDate
    GROUP BY month_index
    ORDER BY month_index
";

try {
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':startDate', $startDate);
    $stmt->bindParam(':endDate', $endDate);
    $stmt->execute();
    
    $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // پر کردن آرایه amounts با داده‌های دیتابیس
    foreach ($monthlyData as $row) {
        $monthIndex = (int)$row['month_index'];
        // تطبیق ماه‌های میلادی با شمسی (فروردین = ماه 1 میلادی - 3 = -2 => تبدیل به 0)
        $adjustedIndex = $monthIndex >= 0 ? $monthIndex : 12 + $monthIndex;
        $result['amounts'][$adjustedIndex] = (float)$row['total_amount'];
    }
    
    echo json_encode($result);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>