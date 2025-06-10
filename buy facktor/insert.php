<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

// اتصال به دیتابیس
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// دریافت اطلاعات ارسال شده از فرم و جلوگیری از حملات تزریق SQL
$id = mysqli_real_escape_string($conn, $_POST['id']);
$name = mysqli_real_escape_string($conn, $_POST['name']);
$stock = mysqli_real_escape_string($conn, $_POST['stock']);
$price = mysqli_real_escape_string($conn, $_POST['price']);
$date = mysqli_real_escape_string($conn, $_POST['date']);

// کوئری برای درج اطلاعات ورودی در دیتابیس
$sql = "INSERT INTO factor (name, stock, price, date) VALUES ('$name', '$stock', '$price', '$date')";

if ($conn->query($sql) === TRUE) {
    echo "Record added successfully";
    // Redirect back to the form page after inserting the record
    header('Location: buyFACKTOR.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// بستن اتصال
$conn->close();
?>  


