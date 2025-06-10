<?php
// اتصال به دیتابیس
$pdo = new PDO("mysql:host=localhost;dbname=salam;charset=utf8", "root", "");

// حذف فاکتور
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM factor WHERE id = ?");
    $stmt->execute([$id]);
    
    // برگشت به صفحه قبلی
    $redirect = isset($_POST['redirect']) ? urldecode($_POST['redirect']) : 'factor.php';
    header("Location: " . $redirect);
    exit;
}

// اگر به این صفحه مستقیماً دسترسی پیدا شد
header("Location: factor.php");
exit;
?>
