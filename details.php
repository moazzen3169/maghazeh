<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

$name = $_GET['name'] ?? '';
$stmt = $conn->prepare("SELECT * FROM all_products WHERE product_name=? ORDER BY id DESC");
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>جزئیات محصول - <?php echo htmlspecialchars($name); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center py-10">

  <div class="w-full max-w-5xl bg-white shadow-md rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">📋 جزئیات محصول: <?php echo htmlspecialchars($name); ?></h2>
    <table class="w-full border-collapse border border-gray-200 text-center">
      <thead>
        <tr class="bg-gray-100 text-gray-600">
          <th class="p-2 border">#</th>
          <th class="p-2 border">تعداد</th>
          <th class="p-2 border">قیمت خرید</th>
          <th class="p-2 border">رنگ</th>
          <th class="p-2 border">تاریخ (شمسی)</th>
          <th class="p-2 border">تاریخ درج (میلادی)</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr class="hover:bg-gray-50">
            <td class="p-2 border"><?php echo $row['id']; ?></td>
            <td class="p-2 border"><?php echo $row['quantity']; ?></td>
            <td class="p-2 border"><?php echo number_format($row['purchase_price']); ?> تومان</td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['color']); ?></td>
            <td class="p-2 border"><?php echo $row['date_shamsi']; ?></td>
            <td class="p-2 border"><?php echo $row['add_date']; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <div class="mt-6 text-center">
      <a href="count_all_products.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">بازگشت</a>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>
