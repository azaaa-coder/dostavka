<?php
// Подключение к базе данных
include_once '../connectBD/connectBD.php';

$message = '';
$product_id = $_GET['id'];


// Получение товара (для отображения названия)
$query = "SELECT name FROM products WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die('Товар не найден.');
}

// Удаление товара
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm'])) {
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$product_id])) {
        header("Location: product.php");
    } else {
        $message = 'Ошибка при удалении товара.';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Удалить товар</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        h1 { color: #333; }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 3px;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .confirm {
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-back {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-back:hover { background-color: #545b62; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Удалить товар</h1>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'успешно') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php else: ?>
            <p>Вы действительно хотите удалить товар: <strong><?php echo htmlspecialchars($product['name']); ?></strong>?</p>
            <form method="post">
                <button type="submit" name="confirm" class="confirm">Да, удалить</button>
            </form>
            <a href="product.php" class="btn-back">Отмена</a>
        <?php endif; ?>

        <!-- <?php if (!empty($message) && strpos($message, 'успешно') !== false): ?>
            <p>Перенаправление на список товаров...</p>
        <?php endif; ?> -->
    </div>
</body>
</html>
