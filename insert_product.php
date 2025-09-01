<?php
$conn = new mysqli("localhost", "root", "", "salam");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("خطا در اتصال: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : null;
    $unit_price = isset($_POST["unit_price"]) ? (float) $_POST["unit_price"] : null;

    if (!empty($name) && $unit_price > 0) {
        $stmt = $conn->prepare("INSERT INTO product_prices (product_name , unit_price) VALUES (?, ?)");
        $stmt->bind_param("sd", $name, $unit_price);

        if ($stmt->execute()) {
            echo "<script>
                    alert('محصول با موفقیت ثبت شد ✅');
                    window.location.href = document.referrer;
                  </script>";
        } else {
            echo "<script>
                    alert('❌ خطا در ذخیره محصول: " . $stmt->error . "');
                    window.location.href = document.referrer;
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('⚠️ لطفاً تمام فیلدها را به درستی وارد کنید');
                window.location.href = document.referrer;
              </script>";
    }
}

$conn->close();
?>
