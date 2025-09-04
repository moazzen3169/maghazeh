<?php
$conn = new mysqli("localhost", "root", "", "salam");

// بررسی نوع درخواست
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ---- آپدیت اطلاعات ----
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $size = $conn->real_escape_string($_POST['size']);
    $color = $conn->real_escape_string($_POST['color']);
    $date = $conn->real_escape_string($_POST['date']);
    $price = floatval($_POST['price']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);

    $sql = "UPDATE products SET 
            name='$name',
            size='$size',
            color='$color',
            date='$date',
            price='$price',
            payment_method='$payment_method'
            WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        echo "خطا در بروزرسانی: " . $conn->error;
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // ---- گرفتن اطلاعات محصول ----
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id=$id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error" => "محصول یافت نشد"]);
    }
}

$conn->close();
?>
