<?php
    require "../connectBD/connectBD.php";
    $name = $_POST['name'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $sql = "INSERT INTO `users`(`name`, `login`, `password`) VALUES ('$name', '$login','$password')";
    $pdo -> query($sql); 
    $products = $pdo->query("SELECT products.name, products.id FROM products")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Всякая еда</title>
</head>
<body>

    <div class="profile-button">
        <a href="#" id="profile-link">Мой профиль</a>
    </div>

    
    <form action="/magazin/index.php" method="post">
        <div class="card" style="width: 18rem;">
            <ul class="list-group list-group-flush">
                
            </ul>
        </div>
    </form>

    <!-- Блок с информацией о профиле  -->
    <div id="profile-modal" class="profile-modal">
        <div class="profile-content">
            <span class="close-btn" id="close-btn">×</span>
            <h2>Мой профиль</h2>
            <p><strong>Логин:</strong> <span id="profile-login"><?php echo $login ?></span></p>
            <p><strong>Имя:</strong> <span id="profile-name"> <?php echo $name ?> </span></p>
            <p><strong>пароль</strong> <?php echo $password ?></p>
        </div>
    </div>
    
    <div class="shop-card">
        <h1>Каталог товаров</h1>

        <!-- Контейнер для товаров -->
        <div class="product-grid">

            <?php foreach ($products as $product): ?>
                <!-- Карточка товара -->
                <div class="product-card card">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <form action="corzinaSession.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-add">
                            <i class="bi bi-cart-plus"></i> В корзину
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
                

    <script>
// Получаем элементы
var profileLink = document.getElementById("profile-link");
var modal = document.getElementById("profile-modal");
var closeBtn = document.getElementById("close-btn");


profileLink.onclick = function(event) {
    event.preventDefault(); 
    modal.style.display = "block";
};


closeBtn.onclick = function() {
    modal.style.display = "none";
};


window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
</script>
</body>
</html>
