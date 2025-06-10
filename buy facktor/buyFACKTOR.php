<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="factor.css">


<style>



.search{
    width: 300px;
    min-height:80px;
    height: auto;
    background-color: #f8f9fa;
    padding:10px;
    margin-left:1170px;
    position: absolute;
    border-radius:10px

}
.search form{
    width: 100%;

}


#search{
    height: 30px;
    width:220px;
    padding:2px 2px 2px 10px;
    border-right:none;
    border-top:1px solid #333;
    border-left:1px solid #333;
    border-bottom:1px solid #333;
    background-color: #f8f9fa;
    margin-left:1px;
    font-family: morabba;
    
    
}

#searchbtn{
    height:36px;
    background-color: #f8f9fa;
    border-left:none;
    border-top:1px solid #333;
    border-right:1px solid #333;
    border-bottom:1px solid #333;
    margin-right:7px;
    margin-left:0px;
    position:absolute;
    cursor: pointer;
    font-family: morabba;
}
#tblserach{
    width:288px;
    margin-left:1px;
    border-top:none;
}

#clear{
    padding:10px 128px;
    border-radius:5px;
    position:relative;
    top:20px;
    border:1px solid #333;
    background-color: #fff;
    text-decoration:none;

}

</style>


</head>
<body>
    <div class="search">
        <form method="GET" >
            <input type="text" name="search" id="search" placeholder="جستجو..."    >
            <input type="submit" value="Search" id="searchbtn">
        </form>

        
<?php
// اتصال به پایگاه داده
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی اتصال
if ($conn->connect_error) {
    die("اتصال به پایگاه داده انجام نشد: " . $conn->connect_error);
}

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

// ساخت و اجرای کوئری جستجو
$sql = "SELECT * FROM factor WHERE name LIKE '%$searchTerm%' ORDER BY id DESC ";
$result = $conn->query($sql);


echo "<table id='tblserach'>
<tr>
            <th>Name</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Date</th>

        </tr>";


// نمایش نتایج در جدول
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['stock'] . '</td>';
        echo '<td>' . $row['price'] . '</td>';
        echo '<td>' . $row['date'] . '</td>';
        echo '</tr>';
    }
    echo "</table>";



} else {
    echo "<p>هیچ نتیجه‌ای یافت نشد.</p>";
}
} else {
    echo "";
}


// بستن اتصال به پایگاه داده
$conn->close();
?>
<a id="clear" href="buyFACKTOR.php">clear</a>

    </div>
    

    <div class="facktor-container">
        <form action="insert.php" method="post">
        <input type="text" name="name" id="name" placeholder="name">
        <input type="number" name="stock" id="stock" placeholder="stock">
        <input type="text" name="price" id="price" placeholder="price" oninput="formatNumber(this)">
        <input type="text" name="date" id="date" placeholder="date">
        <input type="submit" value="submit">
    </form>

    

</div>


<div class="show">


</head>
<body>
<?php
// اتصال به پایگاه داده
$conn = new mysqli("localhost", "root", "", "salam");

// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// اجرای پرس و جو
$sql = "SELECT id, name, stock, price, date, date_added FROM factor";
$result = $conn->query($sql);

// نمایش داده‌ها در یک جدول HTML
echo "<table>
        <tr>
            <th>Name</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Date</th>
            <th>Date Added</th>
            <th>Action</th>
        </tr>";

// خواندن و نمایش رکوردها
while ($row = $result->fetch_assoc()) {
    // فرمت کردن قیمت اگر اطلاعات عددی نبود
    $formattedPrice = is_numeric($row["price"]) ? number_format($row["price"]) : $row["price"];
    
    echo "<tr>
            <td>" . $row["name"] . "</td>
            <td>" . $row["stock"] . "</td>
            <td>" . $formattedPrice . "</td>
            <td>" . $row["date"] . "</td>
            <td>" . $row["date_added"] . "</td>
            <td><button onclick='deleteProduct({$row['id']})'>Delete</button></td>
          </tr>";
}

echo "</table>";

// بستن اتصال به پایگاه داده
$conn->close();
?>

</div>


<script>
function deleteProduct(productId) {
    if (confirm("Are you sure you want to delete this product?")) {
        // Display an alert
        alert("Product will be deleted!");

        // Redirect to another page with the product ID
        window.location.href = "delete_product.php?id=" + productId;
    }
}
</script>

<?php
// بررسی آیا پارامتر success موجود است و برابر 1 است
if(isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<p id='success-msg' style='color: #fff;'>محصول با موفقیت حذف شد</p>";
}
?>

<script>
// مخفی کردن پیام پس از 2 ثانیه و انتقال به صفحه دیگر
setTimeout(function() {
    var element = document.getElementById('success-msg');
    if (element) {
        element.style.display = 'none';
        window.location.href = 'buyFACKTOR.php'; // صفحه مورد نظر برای انتقال
    }
}, 2000); // 2 ثانیه = 2000 میلی‌ثانیه
</script>








</body>
</html>