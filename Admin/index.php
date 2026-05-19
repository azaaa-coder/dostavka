<?php
session_start();
require "../connectBD/connectBD.php";

// Обработка кнопки "Выполнить"
if (isset($_POST['start_processing']) && !empty($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $pdo->prepare("UPDATE orders SET status = 'processing' WHERE id = ?")->execute([$order_id]);
}

// Обработка кнопки "Завершить"
if (isset($_POST['complete_order']) && !empty($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $pdo->prepare("UPDATE orders SET status = 'completed' WHERE id = ?")->execute([$order_id]);
}

// Получаем только активные заказы (не выполненные)
$sql = "
    SELECT
        o.id AS order_id,
        o.customer_name,
        o.created_at,
        o.status,
        GROUP_CONCAT(p.name SEPARATOR ', ') AS products
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.status != 'completed'
    GROUP BY o.id
    ORDER BY
        CASE WHEN o.status = 'processing' THEN 1 ELSE 2 END,
        o.created_at DESC
";
$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админка — Заказы</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            padding: 20px;
            text-align: center;
            border-bottom: none;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-size: 0.9rem;
        }

        .table tbody tr:hover {
            background-color: #f1f7ff;
        }

        .status-pill {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
        }

        .status-new {
            background-color: #198754;
        }

        .status-processing {
            background-color: #ffc107;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .badge-order {
            background: #e7f5ff;
            color: #084298;
            font-size: 0.85em;
            padding: 5px 10px;
            border-radius: 50px;
        }

        .btn-sm {
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            color: #adb5bd;
            margin-bottom: 15px;
        }

        .text-muted {
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="product.php" class="btn btn-success btn-sm" style="font-weight: 500; padding: 8px 14px; border: none; box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);">
                <i class="bi bi-box-seam me-1"></i> Управление товарами
            </a>
            <span><i class="bi bi-receipt-cutoff me-2"></i>Заказы клиентов</span>
            <span class="badge bg-light text-dark"><?= count($orders) ?> активных</span>
        </div>

        <div class="table-responsive">
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <h5>Все заказы выполнены!</h5>
                    <p class="text-muted">Новых заказов пока нет.</p>
                </div>
            <?php else: ?>
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Клиент</th>
                        <th>Товары</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <span class="status-pill status-<?= htmlspecialchars($order['status']) ?>"
                                    title="Статус: <?= $order['status'] === 'new' ? 'Новый' : ($order['status'] === 'processing' ? 'Выполняется' : 'Завершён') ?>">
                                </span>
                            </td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td style="max-width: 300px;">
                                <div class="text-truncate" title="<?= htmlspecialchars($order['products']) ?>">
                                    <?= htmlspecialchars($order['products']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge-order">
                                    <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <?php if ($order['status'] === 'new'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                            <button type="submit" name="start_processing" class="btn btn-warning text-white">
                                                <i class="bi bi-play-circle"></i> Выполнить
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <button type="submit" name="complete_order" class="btn btn-success">
                                            <i class="bi bi-check-circle"></i> Выполнен
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mt-4 text-muted">
        <small>© <?= date('Y') ?> Админ-панель магазина | Управление заказами</small>
    </div>
</div>

</body>
</html>
