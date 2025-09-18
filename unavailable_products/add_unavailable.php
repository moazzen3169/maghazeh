<?php
include "db.php";

// Fetch existing product names for select
$product_names = [];
$result = $conn->query("SELECT DISTINCT product_name FROM product_prices ORDER BY product_name");
while ($row = $result->fetch_assoc()) {
    $product_names[] = $row['product_name'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];

    // پردازش رنگ‌ها و سایزها
    foreach ($_POST['colors'] as $index => $color) {
        if (!empty($color) && isset($_POST['sizes'][$index])) {
            foreach ($_POST['sizes'][$index] as $size) {
                $stmt = $conn->prepare("INSERT INTO unavailable_units (product_name, color, size) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $product_name, $color, $size);
                $stmt->execute();
            }
        }
    }
    echo "<div class='success-message'>✅ اطلاعات با موفقیت ذخیره شد</div>";
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>افزودن محصول ناموجود</title>
<link rel="stylesheet" href="css-library.css">
<link rel="stylesheet" href="unavailable_styles.css">
<script>
let colorIndex = 0;
function addColorField(){
    let colorsDiv = document.getElementById("colorsDiv");
    let div = document.createElement("div");
    div.classList.add("color-block");
    div.innerHTML = `
        <label>رنگ: <select name="colors[${colorIndex}]" class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500" required>
            <option value="مشکی">مشکی</option>
            <option value="سفید">سفید</option>
            <option value="قرمز">قرمز</option>
            <option value="سبز">سبز</option>
            <option value="زرد">زرد</option>
            <option value="خردلی">خردلی</option>
            <option value="کرمی">کرمی</option>
            <option value="قهوه ای">قهوه ای</option>
            <option value="صورتی">صورتی</option>
            <option value="زرشکی">زرشکی</option>
            <option value="توسی">توسی</option>
            <option value="گلبهی">گلبهی</option>
            <option value="بنفش">بنفش</option>
            <option value="آبی">آبی</option>
            <option value="تعویضی">تعویضی</option>
        </select></label><br>
        <label>سایزها:</label><br>
        <div class="checkbox-group">
            ${[36,38,40,42,44,46,48,50,52,54,56,58,60].map(size=>`
                <label class="checkbox-item"><input type="checkbox" name="sizes[${colorIndex}][]" value="${size}">${size}</label>
            `).join('')}
        </div>
    `;
    colorsDiv.appendChild(div);
    colorIndex++;
}
</script>
</head>
<body>
<div class="container">
    <h2>افزودن محصول ناموجود</h2>
    <div class="form-container">
        <form method="post">
            <label>نام محصول: <select name="product_name" required>
                <?php foreach ($product_names as $pname): ?>
                    <option value="<?= htmlspecialchars($pname) ?>"><?= htmlspecialchars($pname) ?></option>
                <?php endforeach; ?>
            </select></label><br><br>

            <div id="colorsDiv"></div>
            <button type="button" class="add-color-btn" onclick="addColorField()"><i class="fas fa-plus"></i> افزودن رنگ</button><br><br>

            <button type="submit" class="submit-btn"><i class="fas fa-save"></i> ذخیره</button>
        </form>
    </div>
    <br>
    <a href="list_unavailable.php" class="link-btn"><i class="fas fa-list"></i> مشاهده لیست محصولات</a>
</div>
</body>
</html>
