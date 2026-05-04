<?php
session_start(); 
require "../connectBD/connectBD.php";

// Очистка корзины
if (isset($_GET['clear'])) {
    unset($_SESSION['basket']);
}


if (isset($_POST['add'])) {
    $product_id = (int)$_POST['product_id'];
    $qty = (int)$_POST['qty'];
    if ($qty > 0) {
        $_SESSION['basket'][$product_id] = $qty;
    }
}

$basket_product = [];
if (!empty($_SESSION['basket'])) {
    $product_ids = array_keys($_SESSION['basket']);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $basket_product = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style1.css">
    <title>Корзина</title>
</head>
<body>
<div class="container">
    <h1>Ваша корзина</h1>
    <?php if (empty($basket_product)): ?>
        <p>Корзина пуста. <a href="index.php">Вернуться в меню</a></p>
    <?php else: ?>
        <table class="table table-bordered">
            <tr>
                <th>Название</th>
                <th>Кол-во</th>
            </tr>
            <?php foreach ($basket_product as $product): ?>
                <?php $qty = $_SESSION['basket'][$product['id']]; ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($qty) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="index.php">вернуться в меню</a>
        <a href="?clear=1" class="btn btn-danger">Очистить корзину</a>
        <a href="/Admin/index.php?id<?php echo $product['id'] ?>" class="btn btn-success">Оформить заказ</a>
    <?php endif; ?>
</div>
</body>
</html>
