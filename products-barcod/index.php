<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <title>مدیریت محصولات</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body class="bg-gray-100 p-6">

  <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-2xl p-6">
    <h1 class="text-2xl font-bold mb-4">افزودن محصول</h1>

    <!-- فرم -->
    <form action="actions.php" method="POST" class="grid grid-cols-3 gap-4">
      <input type="text" name="product_name" placeholder="نام محصول" required class="border p-2 rounded">
      <input type="text" name="color" placeholder="رنگ" required class="border p-2 rounded">
      <input type="text" name="size" placeholder="سایز" required class="border p-2 rounded">
      <button type="submit" name="add" class="col-span-3 bg-blue-500 text-white rounded p-2 hover:bg-blue-600">
        افزودن
      </button>
    </form>
  </div>

  <!-- جدول -->
  <div class="max-w-5xl mx-auto mt-8 bg-white shadow-lg rounded-2xl p-6">
    <h2 class="text-xl font-bold mb-4">لیست محصولات</h2>
    <table class="w-full border-collapse border border-gray-300">
      <thead>
        <tr class="bg-gray-100">
          <th class="border p-2">نام محصول</th>
          <th class="border p-2">رنگ</th>
          <th class="border p-2">سایز</th>
          <th class="border p-2">بارکد</th>
          <th class="border p-2">عملیات</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM barcodes ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td class="border p-2"><?= $row['product_name'] ?></td>
          <td class="border p-2"><?= $row['color'] ?></td>
          <td class="border p-2"><?= $row['size'] ?></td>
          <td class="border p-2">
            <svg class="barcode" jsbarcode-value="<?= $row['barcode_value'] ?>"></svg>
          </td>
          <td class="border p-2 space-x-2">
            <button class="bg-green-500 text-white px-3 py-1 rounded" onclick="openModal('printModal<?= $row['id'] ?>')">پرینت</button>
            <button class="bg-yellow-500 text-white px-3 py-1 rounded" onclick="openModal('editModal<?= $row['id'] ?>')">ویرایش</button>
            <button class="bg-red-500 text-white px-3 py-1 rounded" onclick="openModal('deleteModal<?= $row['id'] ?>')">حذف</button>
          </td>
        </tr>

        <!-- مودال پرینت -->
        <div id="printModal<?= $row['id'] ?>" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">پرینت بارکد</h3>
            <svg class="barcode" jsbarcode-value="<?= $row['barcode_value'] ?>"></svg>
            <button onclick="window.print()" class="bg-blue-500 text-white px-3 py-1 rounded mt-4">پرینت</button>
            <button onclick="closeModal('printModal<?= $row['id'] ?>')" class="bg-gray-400 text-white px-3 py-1 rounded mt-4">بستن</button>
          </div>
        </div>

        <!-- مودال ویرایش -->
        <div id="editModal<?= $row['id'] ?>" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">ویرایش محصول</h3>
            <form action="actions.php" method="POST">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="text" name="product_name" value="<?= $row['product_name'] ?>" class="border p-2 rounded mb-2 w-full">
              <input type="text" name="color" value="<?= $row['color'] ?>" class="border p-2 rounded mb-2 w-full">
              <input type="text" name="size" value="<?= $row['size'] ?>" class="border p-2 rounded mb-2 w-full">
              <button type="submit" name="edit" class="bg-yellow-500 text-white px-3 py-1 rounded">ذخیره</button>
              <button type="button" onclick="closeModal('editModal<?= $row['id'] ?>')" class="bg-gray-400 text-white px-3 py-1 rounded">بستن</button>
            </form>
          </div>
        </div>

        <!-- مودال حذف -->
        <div id="deleteModal<?= $row['id'] ?>" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">آیا مطمئنید؟</h3>
            <form action="actions.php" method="POST">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" name="delete" class="bg-red-500 text-white px-3 py-1 rounded">بله، حذف شود</button>
              <button type="button" onclick="closeModal('deleteModal<?= $row['id'] ?>')" class="bg-gray-400 text-white px-3 py-1 rounded">انصراف</button>
            </form>
          </div>
        </div>

        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    JsBarcode(".barcode").init();

    function openModal(id) {
      document.getElementById(id).classList.remove("hidden");
    }
    function closeModal(id) {
      document.getElementById(id).classList.add("hidden");
    }
  </script>

</body>
</html>
