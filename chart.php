<?php
// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی اتصال
if ($conn->connect_error) {
    die("اتصال به دیتابیس ناموفق بود: " . $conn->connect_error);
}

// اجرای پرس و جو برای دریافت داده‌ها
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$dataFromDatabase = array();

if ($result->num_rows > 0) {
    // دریافت داده‌ها به صورت آرایه
    while($row = $result->fetch_assoc()) {
        $dataFromDatabase[] = $row["price"];
    }
} else {
    echo "هیچ داده‌ای یافت نشد";
}

$conn->close();

// تبدیل آرایه داده‌ها به فرمت JSON برای استفاده در جاوااسکریپت
$dataJSON = json_encode($dataFromDatabase);
?>

<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>نمودار ستونی 1403</title>
<style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
    }
    #chartContainer {
        width: 1150px;
        max-height:300px;
        margin: 0 auto;
        font-family: morabba;
    }

    #myChart{
    font-family: peyda;
}

    h1{
        font-family: morabba;
    }
</style>
</head>
<body>
<h1>نمودار ستونی 1403</h1>
<div id="chartContainer">
    <canvas id="myChart"></canvas>
</div>

<script src="scrip.js"></script>
<script>
    const dataFromDatabase = <?php echo $dataJSON; ?>;
    
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['فروردین', ' اردیبهشت', 'خرداد', ' تیر', ' مرداد', ' شهریور', ' مهر', ' آبان', ' آذر', ' دی', ' بهمن', ' اسفند', ' فروردین', ' اردیبهشت'],
            datasets: [{
                label: 'داده‌ها',
                data: dataFromDatabase,
                backgroundColor: 'rgba(78, 165, 223, 0.86)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>