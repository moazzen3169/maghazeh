<?php
$conn = new mysqli("localhost", "root", "", "salam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// دریافت داده‌های فرم
$id = $_POST['id'] ?? null;
$newName = trim($_POST['new_name'] ?? '');
$color = $_POST['color'] ?? null;
$size = $_POST['size'] ?? null;
$date = $_POST['date'] ?? null;
$price = $_POST['price'] ?? null;
$payment_method = $_POST['payment_method'] ?? 'pos'; // مقدار پیش‌فرض در صورت خالی بودن

// بررسی محصول جدید یا موجود
if ($id === 'add_new' && $newName !== '') {
    // اضافه کردن محصول جدید به جدول product_prices
    $stmt = $conn->prepare("INSERT INTO product_prices (product_name) VALUES (?)");
    $stmt->bind_param("s", $newName);
    $stmt->execute();
    $productId = $stmt->insert_id; // گرفتن id محصول جدید
    $productName = $newName;
    $stmt->close();
} elseif ($id) {
    // گرفتن نام محصول از جدول product_prices
    $stmt = $conn->prepare("SELECT product_name FROM product_prices WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $productName = $result->fetch_assoc()['product_name'] ?? null;
    $stmt->close();
}

if (!$productName) {
    die("محصول نامعتبر است.");
}

// درج در جدول products همراه با فیلد payment_method
$stmt = $conn->prepare("INSERT INTO products (name, color, size, date, price, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $productName, $color, $size, $date, $price, $payment_method);
$stmt->execute();
$stmt->close();

$conn->close();

// بازگشت به صفحه اصلی پس از موفقیت
header("Location: dashboard.php"); // آدرس صفحه اصلی خودت را اینجا بگذار
exit();
?>
