<?php
session_start();
require "../connectBD/connectBD.php";

// Если данные пришли через POST — сохраняем в сессию
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['name'] = $_POST['name'] ?? $_SESSION['name'] ?? '';
    $_SESSION['login'] = $_POST['login'] ?? $_SESSION['login'] ?? '';
    $_SESSION['password'] = $_POST['password'] ?? $_SESSION['password'] ?? '';
}

// Если сессия есть — используем её
$name = $_SESSION['name'] ?? '';
$login = $_SESSION['login'] ?? '';
$password = $_SESSION['password'] ?? '';

$products = $pdo->query("SELECT products.name, products.id FROM products")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Всякая еда — Магазин</title>
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
            max-width: 1200px;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .btn-add {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 0.9rem;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            background: linear-gradient(90deg, #0b5ed7, #0a58ca);
            transform: scale(1.05);
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
            margin-top: 20px;
        }

        .product-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            width: 220px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .product-card h3 {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 15px;
        }

        .corzin-button a {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(90deg, #28a745, #218838);
            color: white;
            padding: 10px 16px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            z-index: 100;
            transition: all 0.3s ease;
        }

        .corzin-button a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
        }
   .logout-button a:hover {
    position: fixed;
    top: 10px;
    right: 20px;
    background: linear-gradient(90deg, #28a745, #218838);
    color: white;
    padding: 225px 16px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    z-index: 100;
    transition: all 0.3s ease;
}



        .profile-button a {
            position: fixed;
            top: 20px;
            left: 20px;
            background: linear-gradient(90deg, #6f42c1, #5a32a3);
            color: white;
            padding: 10px 16px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
            z-index: 100;
            transition: all 0.3s ease;
        }

        .profile-button a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(111, 66, 193, 0.4);
        }

        /* Модальное окно профиля */
        .profile-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        .profile-content {
            background: white;
            margin: 10% auto;
            padding: 30px;
            width: 400px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .close-btn {
            float: right;
            font-size: 24px;
            cursor: pointer;
            color: #6c757d;
            font-weight: bold;
        }

        .close-btn:hover {
            color: #dc3545;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="logout-button">
    <a href="../logout.php"
       onclick="return confirm('Вы уверены, что хотите выйти?');"
       class="btn btn-outline-danger btn-sm d-flex align-items-center"
       style="position: fixed; top: 25px; right: 250px; z-index: 1000; font-size: 0.9rem; border-radius: 70px; padding: 8px 14px; background: white; border: 1px solid #dc3545; color: #dc3545; text-decoration: none; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2); transition: all 0.3s ease;">
        <i class="bi bi-box-arrow-right me-1"></i> Выйти
    </a>
</div>

<style>
    .logout-button a:hover {
        background: #dc3545;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
    }
</style>

<div class="corzin-button">
    <a href="corzin.php">🛒 Перейти в корзину</a>
</div>

<div class="profile-button">
    <a href="#" id="profile-link">👤 Мой профиль</a>
</div>

<!-- Модальное окно профиля -->
<div id="profile-modal" class="profile-modal">
    <div class="profile-content">
        <span class="close-btn" id="close-btn">×</span>
        <h2>Мой профиль</h2>
        <p><strong>Логин:</strong> <span><?= htmlspecialchars($login) ?></span></p>
        <p><strong>Имя:</strong> <span><?= htmlspecialchars($name) ?></span></p>
        <p><strong>Пароль:</strong> ••••••••</p>
    </div>
</div>



<div class="container">
    <h1>Товары</h1>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card card">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <form action="corzinaSession.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="mb-2">
                    <input type="number" name="quantity" value="1" min="1" max="99" class="form-control form-control-sm" style="width: 80px; display: inline-block;">
                    </div>
                    <button type="submit" class="btn btn-add">
                        <i class="bi bi-cart-plus"></i> В корзину
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    const profileLink = document.getElementById("profile-link");
    const modal = document.getElementById("profile-modal");
    const closeBtn = document.getElementById("close-btn");

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
