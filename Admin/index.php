
<?php
require "../connectBD/connectBD.php";



// получаем данные из формы
$users = $pdo -> query("SELECT * FROM `users`") -> fetchAll();
$products = $pdo -> query("SELECT * FROM `products`") -> fetchAll();
$bookings = $pdo -> query("SELECT bookings.id, users.name as name_user, products.name as name_product
FROM bookings
JOIN users on bookings.name_user = users.id
JOIN products on bookings.name_product = products.id")->fetchAll();

$sql = "INSERT INTO `Bookings`(`name_user`, `name_product`) VALUES ('[value-1]','[value-2]')";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все заказы</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
   <div class="container">
       <h1>Всякая еда</h1>
       <p><h5>Администрационная страница</h5>Вкусно и быстро</p>
   </div>
</nav>

<!-- блок заказов -->
<div class="container">
   <?php foreach ($bookings as $booking): ?>
   <div class="booking-card">
       <h3>Заказ № <?php echo $booking['id'] ?> <p>В ожидании</p></h3>
       <table>
           <tr><th>Клиент:</th><td><?= htmlspecialchars($booking['name_user']) ?></td></tr>
           <tr><th>Продукт:</th><td><?= htmlspecialchars($booking['name_product']) ?></td></tr>
           <tr><td colspan="2" style="text-align:center;">
               <a href="delete.php?id=<?= $booking['id'] ?>" class="btn btn-danger btn-delete">Удалить</a>
           </td></tr>
       </table>
   </div>
   <?php endforeach; ?>
</div>
