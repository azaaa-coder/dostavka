<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $quantity = max(1, (int)($_POST['quantity'] ?? 1)); // минимум 1

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Ищем, есть ли уже такой товар в корзине
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity; // увеличиваем количество
            $found = true;
            break;
        }
    }

    // Если нет — добавляем новый
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'quantity' => $quantity
        ];
    }
}

header("Location: index.php");
exit();
?>
