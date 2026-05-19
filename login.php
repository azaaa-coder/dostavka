<?php
session_start();
require "connectBD/connectBD.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if ($user && md5($password) === $user['password']) {
            // Успешный вход
            $_SESSION['name'] = $user['name'];
            $_SESSION['login'] = $user['login'];


            // Перенаправление
            if ($user['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: magazin/index.php");
            }
            exit();
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в аккаунт</title>
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
        .login-card {
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
<div class="login-card">
    <h3 class="text-center mb-4">🔐 Вход в аккаунт</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Логин</label>
            <input type="text" name="login" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Пароль</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Войти</button>
    </form>

    <div class="text-center mt-3">
        <small>Нет аккаунта? <a href="index.php">Зарегистрироваться</a></small>
    </div>
</div>
</body>
</html>
