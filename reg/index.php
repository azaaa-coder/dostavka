<?php 
require "connectBD.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="stylereg.css">
    <title>китайский магазинчик</title>
</head>
<body>
    <div class="card" style="width: 18rem;">
  <ul class="list-group list-group-flush">
    <li class="list-group-item">
        <label>Имя</label>
    <input type="text" id="name" placeholder="введите имя"></li>

    <li class="list-group-item">
        <label>Логин</label>
    <input type=" text" id="login" placeholder="введите логин"></li>

    <li class="list-group-item">
        <label>пароль</label>
    <input type="password" id="password" placeholder="Придумайте пароль"></li>
  <a href="/magazin/index.php" class="btn btn-primary">Зарегестрироваться</a>
</ul>
</div>

</body>
</html>