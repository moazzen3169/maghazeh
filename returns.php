<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>returns</title>


    <style>
        * {
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

.form {

    margin: 20px;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form input {
    font-family:modam;

    direction:rtl;
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.form input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
}



.products {
    
    width:100%;
    padding: 10px;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    grid-gap: 10px;
}

.product {
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
}
.product #green {
    background-color: #61a561;
}
.product #red {
    background-color: pink;
}

#green {
padding:20px;

}

table {
    direction:rtl;
    width: 1000px;
    border-collapse: collapse;
}

th, td {
    font-family:modam;

    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #f2f2f2;
}
    </style>

    
</head>
<body>

<div class="form">
    <form action="returns_post.php" method="post">
        <input type="text" name="name" id="name" placeholder="نام محصول">
        <input type="number" name="stock" id="stock" placeholder="تعداد">
        <input type="text" name="buyer" id="buyer" placeholder="نام خریدار">
        <input type="text" name="date" id="date" placeholder="تاریخ">
        <input type="checkbox" name="green" id="green">
        <input type="submit" value="ثبت">
    </form>
</div>
    

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

// استعلام برای دریافت اطلاعات از دیتابیس
$sql = "SELECT * FROM returns ORDER BY added_date DESC" ;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table class="products">';
    echo '<tr><th>نام محصول</th><th>تعداد</th><th>نام خریدار</th><th>تاریخ</th><th>مرجوع داده شده؟</th></tr>';
    while ($row = $result->fetch_assoc()) {
        $color = $row["green"] ? "green" : "red"; // Set text color based on "green" value
        echo '<tr class="product" style="background-color: ' . $color . ';">';
        echo '<td>' . $row["name"] . '</td>';
        echo '<td>' . $row["stock"] . '</td>';
        echo '<td>' . $row["buyer"] . '</td>';
        echo '<td>' . $row["date"] . '</td>';
        echo '<td>' . ($row["green"] ? "بله" : "خیر") . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo "هیچ رکوردی یافت نشد.";
}

// بستن اتصال
$conn->close();
?>



</body>
</html>