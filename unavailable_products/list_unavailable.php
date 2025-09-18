<?php
include "db.php";

// تغییر وضعیت با AJAX
if (isset($_POST['toggle_id'])) {
    $id = $_POST['toggle_id'];
    $conn->query("UPDATE unavailable_units SET status = IF(status='unavailable','restocked','unavailable') WHERE id=$id");
    exit;
}

// حذف با AJAX
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $conn->query("DELETE FROM unavailable_units WHERE id=$id");
    exit;
}

$result = $conn->query("SELECT * FROM unavailable_units ORDER BY product_name, color, size");
$products = [];
while($row = $result->fetch_assoc()){
    $products[$row['product_name']][$row['color']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>لیست محصولات ناموجود</title>
<link rel="stylesheet" href="css-library.css">
<link rel="stylesheet" href="unavailable_styles.css">
<script>
function toggleStatus(id, el){
    fetch("list_unavailable.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"toggle_id="+id
    }).then(()=>{
        el.classList.toggle("unavailable");
        el.classList.toggle("restocked");
    });
}

function deleteItem(id, el){
    if(confirm("آیا مطمئن هستید که می‌خواهید این مورد را حذف کنید؟")) {
        fetch("list_unavailable.php", {
            method:"POST",
            headers:{"Content-Type":"application/x-www-form-urlencoded"},
            body:"delete_id="+id
        }).then(()=>{
            el.parentElement.remove();
        });
    }
}
</script>
</head>
<body>
<div class="container">
    <h2>لیست محصولات ناموجود</h2>
    <a href="add_unavailable.php" class="link-btn"><i class="fas fa-plus"></i> افزودن محصول جدید</a>
    <div class="product-list">
        <?php foreach($products as $pname=>$colors): ?>
            <div class="product-card">
                <div class="product-header">
                    <i class="fas fa-box"></i> <?= $pname ?>
                </div>
                <?php foreach($colors as $color=>$sizes): ?>
                    <div class="color-section">
                        <div class="color-title">
                            <i class="fas fa-palette"></i> رنگ: <?= $color ?>
                        </div>
                        <div class="size-grid">
                            <?php foreach($sizes as $s): ?>
                                <div class="size-item <?= $s['status'] ?>" onclick="toggleStatus(<?= $s['id'] ?>, this)">
                                    <?= $s['size'] ?>
                                    <button class="delete-btn" onclick="event.stopPropagation(); deleteItem(<?= $s['id'] ?>, this)"><i class="fas fa-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
