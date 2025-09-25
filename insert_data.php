<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("خطا در اتصال به دیتابیس: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $month = $_POST["month"];
    $price = $_POST["price"];

    $stmt = $conn->prepare("INSERT INTO chart (name, price) VALUES (?, ?)");
    $stmt->bind_param("si", $month, $price);

    if ($stmt->execute()) {
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "خطا در درج اطلاعات: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
