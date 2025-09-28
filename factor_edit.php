<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=salam;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $date = $_POST['date'];
        $returned = isset($_POST['returned']) ? 1 : 0;
        $final_price = $price * $stock;

        $stmt = $pdo->prepare("UPDATE factor SET name=?, price=?, stock=?, final_price=?, date=?, returned=? WHERE id=?");
        $stmt->execute([$name, $price, $stock, $final_price, $date, $returned, $id]);

        header("Location: factor.php");
    } catch (PDOException $e) {
        echo "خطا: " . $e->getMessage();
    }
}
?>
