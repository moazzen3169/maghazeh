<?php
// اتصال به دیتابیس
$conn = new mysqli("localhost", "root", "", "salam");

// بررسی خطا در اتصال
if ($conn->connect_error) {
    die("خطا در اتصال به دیتابیس: " . $conn->connect_error);
}

// بررسی ارسال فرم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $size = $conn->real_escape_string($_POST['size']);
    $color = $conn->real_escape_string($_POST['color']);
    $date = $conn->real_escape_string($_POST['date']);
    $price = floatval($_POST['price']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);

    // کوئری آپدیت
    $sql = "UPDATE products SET 
                name='$name',
                size='$size',
                color='$color',
                date='$date',
                price='$price',
                payment_method='$payment_method'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // هدایت به صفحه اصلی یا داشبورد
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        echo "❌ خطا در بروزرسانی: " . $conn->error;
    }
} else {
    echo "درخواست نامعتبر است!";
}

// بستن اتصال
$conn->close();
?>
