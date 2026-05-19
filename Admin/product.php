<?php
// Подключение к базе данных
include_once '../connectBD/connectBD.php';

// Получение всех продуктов
$query = "SELECT * FROM products ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление товарами</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .actions { display: flex; gap: 10px; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px; }
        .edit { background-color: #ffc107; }
        .delete { background-color: #dc3545; }
        .add-btn { display: inline-block; margin: 20px 0; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px; }
        .add-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Список товаров</h1>
        <a href="addProduct.php" class="add-btn">Добавить товар</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?> руб.</td>
                    <td class="actions">
                        <a href="editProduct.php?id=<?php echo $product['id']; ?>" class="btn edit">Редактировать</a>
                        <a href="deleteProduct.php?id=<?php echo $product['id']; ?>" class="btn delete">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Товары не найдены</td>
                </tr>
            <?php endif; ?>
        </table>
        <a href="index.php">Назад в админку</a>
    </div>
</body>
</html>
