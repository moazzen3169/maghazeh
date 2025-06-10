<?php
// اتصال به دیتابیس
$conn = new mysqli("localhost", "root", "", "salam");

// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $productId = $_GET['id'];

    // کوئری برای حذف محصول با آیدی مشخص
    $sql = "DELETE FROM factor WHERE id = $productId";

    if ($conn->query($sql) === TRUE) {
        // حذف موفقیت‌آمیز، انتقال به صفحه اصلی با پیام
        header("Location: buyFACKTOR.php?success=1");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid product ID.";
}

// بستن اتصال
$conn->close();
?>