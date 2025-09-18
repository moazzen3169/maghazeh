<?php
$servername = "localhost";
$username   = "root";   // اگر یوزرنیم شما فرق می‌کنه تغییر بده
$password   = "";       // پسورد دیتابیس
$dbname     = "salam"; // اسم دیتابیس خودت رو بذار

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
