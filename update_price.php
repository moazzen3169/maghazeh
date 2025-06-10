<?php
// اتصال به دیتابیس
$conn = new mysqli("localhost", "root", "", "salam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// تنظیم کدینگ به UTF-8
$conn->set_charset("utf8mb4");

// پردازش فرم ارسال شده
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name']) && isset($_POST['unit_price'])) {
    $productName = $conn->real_escape_string($_POST['product_name']);
    $unitPrice = (float)$_POST['unit_price'];
    
    // بررسی وجود رکورد
    $checkSql = "SELECT id FROM product_prices WHERE product_name = '$productName'";
    $checkResult = $conn->query($checkSql);
    
    if ($checkResult && $checkResult->num_rows > 0) {
        // به‌روزرسانی قیمت موجود
        $updateSql = "UPDATE product_prices SET unit_price = $unitPrice, updated_at = NOW() WHERE product_name = '$productName'";
    } else {
        // درج رکورد جدید
        $updateSql = "INSERT INTO product_prices (product_name, unit_price) VALUES ('$productName', $unitPrice)";
    }
    
    if ($conn->query($updateSql)) {
        // در صورت موفقیت، برگشت به صفحه قبلی با پیام موفقیت
        header("Location: products.php?success=1");
        exit();
    } else {
        // در صورت خطا
        header("Location: products.php?error=1&message=" . urlencode($conn->error));
        exit();
    }
} else {
    header("Location: products.php");
    exit();
}
?>