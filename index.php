<?php
require "connectBD/connectBD.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="stylereg.css">
    <title>Всякая еда</title>
</head>
<body>
    <form action="/magazin/index.php" method="post">
    <div class="card show" style="width: 18rem;">
    <ul class="list-group list-group-flush">
    <li class="list-group-item">
        <!-- <label>Имя</label> -->
    <input type="text" id="name" name="name" placeholder="введите имя" required></li>
    <li class="list-group-item">
        <!-- <label>Логин</label> -->
    <input type="text" id="login" name="login" placeholder="введите логин" required></li>
    <li class="list-group-item">
        <!-- <label>пароль</label> -->
    <input type="password" id="password" name="password" placeholder="Придумайте пароль" required></li>
  <button class="btn btn-primary">Зарегестрироваться</button>
</ul>
</div>
</form>
</body>
</html>
