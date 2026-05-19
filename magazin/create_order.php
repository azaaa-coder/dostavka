<?php
session_start();
require "../connectBD/connectBD.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $cart = $_SESSION['cart'] ?? [];

    if (empty($customer_name) || empty($cart)) {
        die("Имя и корзина обязательны.");
    }

    try {
        $pdo->beginTransaction();

        // Создаём заказ
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name) VALUES (?)");
        $stmt->execute([$customer_name]);
        $order_id = $pdo->lastInsertId();

        // Добавляем товары с количеством
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        foreach ($cart as $item) {
            $stmt->execute([$order_id, $item['product_id'], $item['quantity']]);
        }

        $pdo->commit();
        unset($_SESSION['cart']);

        header("Location: index.php?status=order_success");
        exit();

    } catch (Exception $e) {
        $pdo->rollback();
        error_log("Ошибка заказа: " . $e->getMessage());
        die("Не удалось оформить заказ.");
    }
}

header("Location: corzin.php");
exit();
?>
