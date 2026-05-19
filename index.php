<?php
session_start();
require "connectBD/connectBD.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    if (empty($name) || empty($login) || empty($password)) {
        $error = 'Заполните все поля';
    } elseif (strlen($password) < 3) {
        $error = 'Пароль должен быть не менее 3 символов';
    } else {
        // Проверяем, занят ли логин
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);

        if ($stmt->fetch()) {
            $error = 'Логин уже занят';
        } else {
            // Хэшируем пароль через md5 (временно)
            $hashed = md5($password);

            // Вставляем пользователя
            $stmt = $pdo->prepare("INSERT INTO users (name, login, password, role) VALUES (?, ?, ?, 'user')");
            if ($stmt->execute([$name, $login, $hashed])) {
                $success = 'Регистрация успешна! <a href="login.php">Войти</a>';
            } else {
                $error = 'Ошибка при регистрации';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            width: 400px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: white;
        }
        .btn-primary {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
            border: none;
        }
    </style>
</head>
<body>
<div class="register-card">
    <h3 class="text-center mb-4">📝 Регистрация</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php else: ?>
        <form method="POST">
            <div class="mb-3">
                <label>Имя</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Логин</label>
                <input type="text" name="login" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Пароль</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
        </form>

        <div class="text-center mt-3">
            <small>Есть аккаунт? <a href="login.php">Войти</a></small>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
