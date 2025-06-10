<?php
// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("اتصال به دیتابیس با خطا مواجه شد: " . $conn->connect_error);
}




// دریافت آیدی محصول از روی لینک کلیک شده
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // حذف محصول از دیتابیس با استفاده از آیدی
    // این بخش را باید با توجه به ساختار و نحوه ذخیره‌سازی داده‌های محصول در دیتابیس خود تغییر دهید
    // مثال: $query = "DELETE FROM products WHERE id = $product_id";
    $sql = "DELETE FROM products WHERE id = $product_id";
    if ($conn->query($sql) === TRUE) {
        echo "محصول با موفقیت حذف شد.";
    } else {
        echo "خطا در حذف محصول: " . $conn->error;
    }
  
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        // نمایش پیغام خطا
        echo "خطا در حذف محصول.";
    }






