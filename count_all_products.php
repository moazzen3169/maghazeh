<?php
// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// ذخیره داده جدید
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "add") {
    $product_name   = $_POST['product_name'];
    $quantity       = (int)$_POST['quantity'];
    $purchase_price = (float)$_POST['purchase_price'];
    $color          = $_POST['color'];
    $date_shamsi    = $_POST['date_shamsi'];

    if (!empty($date_shamsi)) {
        $stmt = $conn->prepare("INSERT INTO all_products (product_name, quantity, purchase_price, color, date_shamsi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sidss", $product_name, $quantity, $purchase_price, $color, $date_shamsi);
        $stmt->execute();
        $stmt->close();
    }
}

// ویرایش محصول
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "edit") {
    $id            = (int)$_POST['id'];
    $product_name  = $_POST['product_name'];
    $quantity      = (int)$_POST['quantity'];
    $purchase_price= (float)$_POST['purchase_price'];
    $color         = $_POST['color'];
    $date_shamsi   = $_POST['date_shamsi'];

    $stmt = $conn->prepare("UPDATE all_products SET product_name=?, quantity=?, purchase_price=?, color=?, date_shamsi=? WHERE id=?");
    $stmt->bind_param("sidssi", $product_name, $quantity, $purchase_price, $color, $date_shamsi, $id);
    $stmt->execute();
    $stmt->close();
}

// حذف محصول
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "delete") {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM all_products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// محاسبه مجموع‌ها
$total_quantity = 0;
$total_amount  = 0;

$result = $conn->query("SELECT SUM(quantity) as total_qty, SUM(quantity * purchase_price) as total_amount FROM all_products");
if ($row = $result->fetch_assoc()) {
    $total_quantity = $row['total_qty'] ?? 0;
    $total_amount   = $row['total_amount'] ?? 0;
}

// لیست محصولات
$products = $conn->query("SELECT * FROM all_products ORDER BY id DESC");

// گروه‌بندی محصولات بر اساس نام
$groups = $conn->query("
    SELECT product_name, 
           SUM(quantity) as total_qty, 
           SUM(quantity * purchase_price) as total_amount 
    FROM all_products 
    GROUP BY product_name 
    ORDER BY total_amount DESC
");
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>مدیریت محصولات موجود</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/persian-date/dist/persian-date.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/persian-datepicker/dist/js/persian-datepicker.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker/dist/css/persian-datepicker.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="color.css">

  <style>
    * {
        font-family: peyda;
    }

    body {
        font-family: peyda;
        background-color: var(--color-bg);
    }

    .glass-card {
        background: var(--color-card-bg);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        box-shadow: 0 4px 6px var(--color-shadow);
    }

    .sidebar {
        transition: all 0.3s ease;
    }

    .sidebar-item:hover {
        background-color: var(--color-hover-bg);
    }

    .product-table tr:nth-child(even) {
        background-color: var(--color-even-row);
    }

    .product-table tr:hover {
        background-color: var(--color-hover-row);
    }

    .form-input {
        transition: all 0.3s ease;
    }

    .form-input:focus {
        box-shadow: 0 0 0 3px var(--color-input-focus);
    }

    #date-container {
        margin-left: 30px;
        color: var(--color-text);
    }
  </style>
</head>

<body class="bg-gray-50" >
  <div class="flex flex-col md:flex-row h-screen overflow-hidden">
    <!-- side bar-->
    <?php include("sidebar.php"); ?>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto relative">
      <div id="overlay" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden"></div>
      <!-- Top Navigation -->
      <?php include("header.php"); ?>

      <!-- Dashboard Content -->
      <main class="p-6">

  <!-- فرم افزودن -->
  <div style="min-width: 100%;" class="w-full max-w-xl bg-white shadow-xl rounded-2xl p-8">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">➕ افزودن محصول</h2>
    
    <form method="POST" action="" class="space-y-4">
      <input type="hidden" name="action" value="add">

      <div>
        <label class="block mb-1 text-gray-600">نام محصول:</label>
        <input type="text" name="product_name" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
      </div>

      <div>
        <label class="block mb-1 text-gray-600">تعداد:</label>
        <input type="number" name="quantity" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
      </div>

      <div>
        <label class="block mb-1 text-gray-600">قیمت خرید:</label>
        <input type="text" name="purchase_price" class="price-input w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
      </div>

      <div>
        <label class="block mb-1 text-gray-600">رنگ:</label>
        <select name="color" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
          <option value="">انتخاب رنگ</option>
          <option value="مشکی">مشکی</option>
          <option value="سفید">سفید</option>
          <option value="قرمز">قرمز</option>
          <option value="آبی">آبی</option>
          <option value="سبز">سبز</option>
          <option value="صورتی">صورتی</option>
          <option value="بنفش">بنفش</option>
          <option value="زرد">زرد</option>
          <option value="کرم">کرم</option>
          <option value="خاکستری">خاکستری</option>
        </select>
      </div>

      <div>
        <label class="block mb-1 text-gray-600 flex items-center gap-2">
          تاریخ (امروز: 
          <span id="todayLabel" class="text-blue-600 font-bold cursor-copy select-all"></span>
          )
        </label>
        <input type="text" id="date_shamsi_show" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
        <input type="hidden" id="date_shamsi" name="date_shamsi">
      </div>

      <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg font-semibold">ثبت محصول</button>
    </form>
  </div>

  <!-- گزارش کلی -->
  <div style="min-width: 100%;" class="w-full max-w-xl mt-8 bg-white shadow-md rounded-xl p-6">
    <h3 class="text-lg font-bold mb-4 text-gray-700">📊 گزارش کلی</h3>
    <p class="text-gray-600">کل تعداد: <span class="font-bold text-blue-600"><?php echo $total_quantity; ?></span></p>
    <p class="text-gray-600">مجموع مبلغ کل: <span class="font-bold text-green-600"><?php echo number_format($total_amount, 0); ?> تومان</span></p>
  </div>

  <!-- جدول گروه‌بندی محصولات -->
<div style="min-width: 100%;" class="w-full max-w-5xl mt-8 bg-white  shadow-md rounded-xl p-6 overflow-x-auto">
  <h3 class="text-xl font-bold mb-4 text-gray-700">📦 محصولات گروه‌بندی‌شده</h3>
  <table  class="w-full border-collapse border border-gray-200 text-center">
    <thead>
      <tr class="bg-gray-100 text-gray-600">
        <th class="p-2 border">نام محصول</th>
        <th class="p-2 border">تعداد کل</th>
        <th class="p-2 border">مبلغ کل</th>
        <th class="p-2 border">جزئیات</th>
      </tr>
    </thead>
    <tbody>
      <?php while($g = $groups->fetch_assoc()): ?>
        <tr class="hover:bg-gray-50">
          <td class="p-2 border font-bold"><?php echo htmlspecialchars($g['product_name']); ?></td>
          <td class="p-2 border text-blue-600"><?php echo $g['total_qty']; ?></td>
          <td class="p-2 border text-green-600"><?php echo number_format($g['total_amount']); ?> تومان</td>
          <td class="p-2 border">
            <a href="details.php?name=<?php echo urlencode($g['product_name']); ?>" 
               class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded inline-flex items-center gap-1">
              <i class="fa-solid fa-eye"></i> نمایش
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>


  <!-- جدول محصولات -->
  <div  style="min-width: 100%;"class="w-full max-w-6xl mt-8 bg-white shadow-md rounded-xl p-6 overflow-x-auto">
    <h3 class="text-lg font-bold mb-4 text-gray-700">📋 لیست محصولات</h3>
    <table class="w-full border-collapse border border-gray-200 text-center">
      <thead>
        <tr class="bg-gray-100 text-gray-600">
          <th class="p-2 border">#</th>
          <th class="p-2 border">نام محصول</th>
          <th class="p-2 border">تعداد</th>
          <th class="p-2 border">قیمت خرید</th>
          <th class="p-2 border">رنگ</th>
          <th class="p-2 border">تاریخ (شمسی)</th>
          <th class="p-2 border">تاریخ درج (میلادی)</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $products->fetch_assoc()): ?>
          <tr class="hover:bg-gray-50">
            <td class="p-2 border"><?php echo $row['id']; ?></td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td class="p-2 border"><?php echo $row['quantity']; ?></td>
            <td class="p-2 border"><?php echo number_format($row['purchase_price']); ?></td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['color']); ?></td>
            <td class="p-2 border"><?php echo $row['date_shamsi']; ?></td>
            <td class="p-2 border"><?php echo $row['add_date']; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

        <script>
          // تاریخ امروز
          document.getElementById("todayLabel").innerText = new persianDate().format("YYYY/MM/DD");

          // تقویم
          $("#date_shamsi_show").persianDatepicker({
              format: "YYYY/MM/DD",
              altField: "#date_shamsi",
              altFormat: "YYYY/MM/DD",
              initialValue: true,
              autoClose: true
          });
          document.getElementById("date_shamsi").value = new persianDate().format("YYYY/MM/DD");

          // --- مدیریت جداکننده قیمت ---
          function formatPriceInput(input) {
            let value = input.value.replace(/,/g, "");
            if (!isNaN(value) && value !== "") {
              input.value = Number(value).toLocaleString("en-US");
            }
          }
          function cleanPriceInputs(form) {
            form.querySelectorAll(".price-input").forEach(input => {
              input.value = input.value.replace(/,/g, "");
            });
          }
          document.querySelectorAll(".price-input").forEach(input => {
            input.addEventListener("input", () => formatPriceInput(input));
          });
          document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", () => cleanPriceInputs(form));
          });
        </script>
      </main>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>
