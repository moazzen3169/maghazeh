<?php
include 'db.php';

if (isset($_POST['add'])) {
    $product_name = $_POST['product_name'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $barcode_value = uniqid(); // بارکد یکتا

    $conn->query("INSERT INTO barcodes (product_name, color, size, barcode_value) VALUES ('$product_name', '$color', '$size', '$barcode_value')");
    header("Location: index.php");
    exit;
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $color = $_POST['color'];
    $size = $_POST['size'];

    $conn->query("UPDATE barcodes SET product_name='$product_name', color='$color', size='$size' WHERE id=$id");
    header("Location: index.php");
    exit;
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM barcodes WHERE id=$id");
    header("Location: index.php");
    exit;
}
?>
