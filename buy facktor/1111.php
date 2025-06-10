<div class="book">
    <form method="post" action="1111.php">
        <input type="text" name="name" id="name" placeholder="name">
        <input type="text" name="writer" id="writer" placeholder="writer">
        <input type="text" name="publisher" id="publisher" placeholder="publisher">
        <input type="text" name="dublor" id="dublor" placeholder="dublor">
        <input type="text" name="shabak" id="shabak" placeholder="shabak">
        <input type="text" name="paper_count" id="paper_count" placeholder="paper-count">
        <input type="text" name="date_made" id="date_made" placeholder="date_made-made">
        <input type="text" name="price" id="price" placeholder="price">
        <input type="text" name="stock" id="stock" placeholder="stock">
        <input type="submit" value="submit">
    </form>
</div>

<!-- submit.php -->
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ocean";

// اتصال به دیتابیس
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// دریافت اطلاعات ارسال شده از فرم
$name = $_POST['name'];
$writer = $_POST['writer'];
$publisher = $_POST['publisher'];
$dublor = $_POST['dublor'];
$shabak = $_POST['shabak'];
$paper_count = $_POST['paper_count'];
$date_made = $_POST['date_made'];
$price = $_POST['price'];
$stock = $_POST['stock'];

// کوئری برای درج اطلاعات ورودی در دیتابیس
$sql = "INSERT INTO book (name, writer, publisher, dublor, shabak, paper_count, date_made, price, stock) 
        VALUES ('$name', '$writer', '$publisher', '$dublor', '$shabak', '$paper_count', '$date_made', '$price', '$stock')";

if ($conn->query($sql) === TRUE) {
    echo "Record added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// بستن اتصال
$conn->close();
?>