
<div class="sec">


<div class="section2">
            <?php
// مشخصات اتصال به دیتابیس
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'salam';

// اتصال به دیتابیس
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("خطا در اتصال به دیتابیس: " . $conn->connect_error);
}

// دستور SQL برای بازیابی قیمت ها
$sql = "SELECT SUM(price) AS total_price FROM products WHERE date LIKE '1404/1/%'";

// اجرای دستور SQL و دریافت نتیجه
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalPrice = number_format($row["total_price"], 0, '.', ',') . ",000"; // افزودن سه رقم صفر به انتهای عدد

} else {
    echo "هیچ قیمتی یافت نشد.";
}

// بستن اتصال به دیتابیس
$conn->close();
?>

<div class="product-price">
<p id="total_price"><?php echo $totalPrice; ?></p>
</div>


            </div>

            <div class="section1">

            <?php
// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی اتصال
if ($conn->connect_error) {
    die("اتصال به دیتابیس با خطا مواجه شد: " . $conn->connect_error);
}

// پرس و جو برای دریافت تعداد محصولات
$sql = "SELECT COUNT(*) as total FROM products WHERE date LIKE '1404/1/%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalProducts = $row["total"];
} else {
    $totalProducts = 0;
}
?>

<!-- نمایش تعداد محصولات در صفحه وب -->
<div class="product-count">
<p id="product-count-value"><?php echo $totalProducts; ?></p>
    
</div>

            </div>


</div>





<div class="proside">
    <table>
    <tr>
      <th>نام</th>
      <th>سایز</th>
      <th>رنگ</th>
      <th>تاریخ</th>
      <th>قیمت</th>
    
    </tr>
    
    <?php



    // برقراری ارتباط با دیتابیس
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'salam';

    $connection = mysqli_connect($host, $username, $password, $database);

    // بررسی موفقیت اتصال
    if (mysqli_connect_errno()) {
        die('خطا در اتصال به دیتابیس: ' . mysqli_connect_error());
    }

    // استعلام برای دریافت اطلاعات
    $query = "SELECT * FROM products WHERE date LIKE '1404/1/%' ORDER BY id DESC";
    $result = mysqli_query($connection, $query);

    // بررسی موفقیت استعلام
    if ($result) {
        // نمایش اطلاعات
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['size'] . '</td>';
            echo '<td>' . $row['color'] . '</td>';
            echo '<td>' . $row['date'] . '</td>';
            echo '<td>' . $row['price'] . '</td>';
            
            echo '</tr>';
        }
    } else {
        echo 'خطا در استعلام: ' . mysqli_error($connection);
    }

    // بستن اتصال به دیتابیس
    mysqli_close($connection);
    ?>
  </table>
    </div>





    <style>

.proside {
    margin: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-family: morabba;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #fff;
}

tr:nth-child(even) {
    background-color: #7e7e7e;
}


.sec{
    display:flex;
    align-items:center;
    justify-content:center;
    margin:10px;
    font-family: morabba;

}

.section1, .section2{

    display:flex;
    padding:5px;
    border:1px solid #000;
    margin: 0px 10px;


}



    </style>