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
$sql = "SELECT number, name FROM count";
$result = $conn->query($sql);

$dataFromDatabase = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dataFromDatabase[$row["name"]] = $row["number"];
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
<title>نمودار دایره‌ای از داده‌های دیتابیس</title>
<style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
    }
    #circleCHART {
        width: 100px;
        margin: 0 auto;
    }
    h1{
        font-family: morabba;
    }
</style>
</head>
<body>
<h1>نمودار دایره‌ای 1403</h1>
<div id="circleCHART">
    <canvas id="myChart"></canvas>
</div>

<script src="circle.js"></script>
<script>
const dataFromDatabase = <?php echo $dataJSON; ?>;
const labels = Object.keys(dataFromDatabase);
const values = Object.values(dataFromDatabase);

const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'داده‌ها',
            data: values,
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