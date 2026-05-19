<?php
// Подключение к базе данных
include_once '../connectBD/connectBD.php';

$message = '';
$product = null;
$product_id = $_GET['id'] ?? null;

// Проверка ID
if (!$product_id || !is_numeric($product_id)) {
    die('Некорректный ID товара.');
}

// Получение товара
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die('Товар не найден.');
}

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);

    if (empty($name) || empty($price)) {
        $message = 'Заполните все поля!';
    } else if (!is_numeric($price) || $price <= 0) {
        $message = 'Цена должна быть положительным числом!';
    } else {
        $query = "UPDATE products SET name = ?, price = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$name, $price, $product_id])) {
            $message = 'Товар успешно обновлён!';
            // Обновляем данные в переменной
            $product['name'] = $name;
            $product['price'] = $price;
        } else {
            $message = 'Ошибка при обновлении товара.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать товар</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        h1 { color: #333; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .btn {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn:hover { background-color: #0056b3; }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 3px;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Редактировать товар</h1>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'успешно') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="name">Название товара</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Цена (руб.)</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <button type="submit" class="btn">Сохранить изменения</button>
        </form>

        <br>
        <a href="product.php">Назад к товарам</a>
    </div>
</body>
</html>
