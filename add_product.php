<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'salam';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // دریافت داده‌های ارسالی
    $product_name = $_POST['product_name'] ?? '';
    $unit_price = (float)$_POST['unit_price'] ?? 0;
    // اعتبارسنجی داده‌ها
    if (empty($product_name)) {
        throw new Exception('نام محصول نمی‌تواند خالی باشد');
    }
    
    if (!is_numeric($unit_price) || $unit_price < 0) {
        throw new Exception('قیمت باید عددی باشد');
    }
    
    // ذخیره در دیتابیس
    $stmt = $conn->prepare("INSERT INTO product_prices (product_name, unit_price) VALUES (?, ?)");
    $stmt->execute([$product_name, $unit_price]);
    
    echo json_encode([
        'success' => true,
        'product_name' => $product_name
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطای دیتابیس: ' . $e->getMessage()
    ]);
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>