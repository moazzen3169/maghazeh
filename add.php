

<?php
    // اتصال به پایگاه داده
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "salam";

    $conn = mysqli_connect($servername, $username, $password, $database);

    // بررسی اتصال
    if (!$conn) {
        die("خطا در اتصال به پایگاه داده: " . mysqli_connect_error());
    }

    // پردازش فرم در صورت ارسال
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // دریافت مقادیر از فرم
        $name = $_POST["name"];
        $color = $_POST["color"];
        $size = $_POST["size"];
        $date = $_POST["date"];
        $price = $_POST["price"];

        // استفاده از prepared statement برای جلوگیری از SQL Injection
        $stmt = $conn->prepare("INSERT INTO products (name, color, size, date, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $color, $size, $date,$price);

        // درج اطلاعات در جدول "order"
        $sql = "INSERT INTO orders (name, color, size,price) VALUES ('$name', '$color', '$size', '$price')";
        if ($conn->query($sql) === false) {
            echo "خطا در درج اطلاعات در جدول order: " . $conn->error;
}

        // اجرای استعلام
        if ($stmt->execute()) {

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();


        } else {
            echo "خطا در ذخیره اطلاعات: " . $stmt->error;
        }

        // بستن prepared statement
        $stmt->close();
    }
    ?>
