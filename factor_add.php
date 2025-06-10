<?php
// اتصال به دیتابیس
$pdo = new PDO("mysql:host=localhost;dbname=salam;charset=utf8", "root", "");

// پردازش فرم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $date = $_POST['date'];
    $returned = isset($_POST['returned']) ? 1 : 0;
    $final_price = $price * $stock;
    $redirect = $_POST['redirect'] ?? 'factor.php';

    try {
        $stmt = $pdo->prepare("INSERT INTO factor (name, price, stock, final_price, date, returned) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $stock, $final_price, $date, $returned]);
        
        // برگشت به صفحه قبلی
        header("Location: " . $redirect);
        exit;
    } catch (PDOException $e) {
        // در صورت خطا به صفحه اصلی با پیام خطا برگردید
        header("Location: factor.php?error=1");
        exit;
    }
}

// اگر مستقیماً به این صفحه دسترسی پیدا شد
header("Location: factor.php");
exit;
?>