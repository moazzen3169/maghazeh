
<?php
// اطلاعات دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

// اتصال به دیتابیس
$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// دریافت اطلاعات ارسال شده از فرم
$name = $_POST['name'];
$stock = $_POST['stock'];
$buyer = $_POST['buyer'];
$date = $_POST['date'];
$green = isset($_POST['green']) ? 1 : 0; // اگر چک باکس انتخاب شده باشد، مقدار 1 ذخیره می‌شود، در غیر این صورت 0

// استفاده از prepared statements برای جلوگیری از SQL injection
$stmt = $conn->prepare("INSERT INTO returns (name, stock, buyer, date, green) VALUES (?, ?,?, ?, ?)");
$stmt->bind_param("sisis", $name, $stock, $buyer, $date, $green);

// اجرای استعلام
$stmt->execute();

// بستن اتصال   
$stmt->close();
$conn->close();

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
