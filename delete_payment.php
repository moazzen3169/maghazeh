<?php
// delete_payment.php - نسخه موقت با اتصال دستی

// شروع session
session_start();

// 1. تنظیمات اتصال به دیتابیس (مقادیر را با اطلاعات خود جایگزین کنید)
$db_host = 'localhost';      // آدرس سرور دیتابیس
$db_user = 'root';           // نام کاربری دیتابیس (معمولاً root در محیط توسعه)
$db_pass = '';               // رمز عبور دیتابیس (خالی در wamp معمولاً)
$db_name = 'salam';    // نام دیتابیس شما

// 2. ایجاد اتصال
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset("utf8mb4");
    
    if ($conn->connect_error) {
        throw new Exception("خطا در اتصال به دیتابیس: " . $conn->connect_error);
    }

    // 3. بررسی وجود ID در پارامتر GET
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        $_SESSION['error'] = "شناسه پرداخت نامعتبر است";
        header("Location: payments_list.php");
        exit;
    }

    $payment_id = (int)$_GET['id'];

    // 4. بررسی وجود رکورد قبل از حذف
    $check_sql = "SELECT id FROM payments WHERE id = $payment_id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows === 0) {
        $_SESSION['error'] = "پرداخت مورد نظر یافت نشد";
        header("Location: payments_list.php");
        exit;
    }

    // 5. انجام عملیات حذف
    $delete_sql = "DELETE FROM payments WHERE id = $payment_id";
    if ($conn->query($delete_sql)) {
        $_SESSION['success'] = "پرداخت با موفقیت حذف شد";
    } else {
        throw new Exception("خطا در حذف پرداخت: " . $conn->error);
    }

    // 6. بستن اتصال
    $conn->close();

    // 7. بازگشت به صفحه لیست پرداخت‌ها
    header("Location:pay.php");
    exit;

} catch (Exception $e) {
    // نمایش خطا (فقط در محیط توسعه)
    die("خطا: " . $e->getMessage());
}