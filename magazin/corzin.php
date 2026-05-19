<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

require "../connectBD/connectBD.php";

// Инициализация корзины
$cart = $_SESSION['cart'] ?? [];

$products = [];

if (!empty($cart)) {
    $ids = array_column($cart, 'product_id');
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $db_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($db_products as $p) {
        foreach ($cart as $item) {
            if ($item['product_id'] == $p['id']) {
                $p['quantity'] = $item['quantity'];
                $products[] = $p;
                break;
            }
        }
    }
}

// === Обработка удаления одного товара ===
if (isset($_GET['remove']) && isset($_SESSION['cart'])) {
    $remove_id = (int)$_GET['remove'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($remove_id) {
        return $item['product_id'] != $remove_id;
    });
    header("Location: corzin.php");
    exit();
}

// === Обработка очистки всей корзины ===
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header("Location: corzin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина — Всякая еда</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .list-group-item {
            background: #f8f9ff;
            margin-bottom: 8px;
            border-radius: 10px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13, 110, 253, 0.3);
        }

        .btn-danger {
            background: linear-gradient(90deg, #dc3545, #c82333);
            border: none;
            border-radius: 50px;
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        .btn-danger:hover {
            transform: scale(1.05);
        }

        .btn-outline-secondary {
            border-radius: 50px;
            font-size: 0.95rem;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #adb5bd;
            margin-bottom: 15px;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .total-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #0d6efd;
            text-align: right;
            margin: 15px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-4">
        <h2 class="text-center">🛒 Ваша корзина</h2>

        <!-- Кнопка очистки корзины -->
        <?php if (!empty($products)): ?>
            <form method="POST" style="text-align: right; margin-bottom: 20px;">
                <button type="submit" name="clear_cart" class="btn btn-outline-secondary btn-sm">
                    🗑 Очистить всё
                </button>
            </form>
        <?php endif; ?>

        <?php if (empty($products)): ?>
            <div class="empty-state">
                <i class="bi bi-cart-x"></i>
                <h5>Корзина пуста</h5>
                <p>Добавьте товары в корзину на главной странице.</p>
                <a href="index.php" class="btn btn-primary">Перейти в магазин</a>
            </div>
        <?php else: ?>
            <ul class="list-group mb-4">
                <?php $total = 0; ?>
                <?php foreach ($products as $product):
                    $price = $product['price'] ?? 0;
                    $sum = $price * $product['quantity'];
                    $total += $sum;
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <?= htmlspecialchars($product['name']) ?> × <?= $product['quantity'] ?>
                            <br>
                            <small class="text-muted"><?= number_format($price, 2) ?> ₽ за шт.</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <strong><?= number_format($sum, 2) ?> ₽</strong>
                            <!-- Кнопка удаления товара -->
                            <form method="GET" style="display: inline;">
                                <button type="submit" name="remove" value="<?= $product['id'] ?>" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="total-price">
                Итого: <?= number_format($total, 2) ?> ₽
            </div>

            <form action="create_order.php" method="post">
                <div class="mb-3">
                    <label for="customer_name" class="form-label"><strong>Ваше имя:</strong></label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control"
                           value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-receipt"></i> Оформить заказ
                </button>
            </form>

            <a href="index.php" class="btn btn-outline-secondary w-100">Продолжить покупки</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
