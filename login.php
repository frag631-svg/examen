<?php
session_start();

// Если пользователь уже авторизован, перенаправляем в профиль
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$error = '';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Валидация
    if (empty($email) || empty($password)) {
        $error = 'Пожалуйста, заполните все поля';
    } else {
        // Простая проверка (в реальном проекте - проверка в БД)
        // Демо-пользователь: admin@library.com / password123
        if ($email === 'admin@library.com' && $password === 'password123') {
            // Успешная авторизация
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Администратор';
            $_SESSION['user_email'] = $email;
            
            header('Location: profile.php');
            exit;
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Библиотечная система</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo">
                    <h1>📚 Библиотека</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="catalog.php">Каталог</a></li>
                    <li><a href="profile.php">Личный кабинет</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                </ul>
                <div class="auth-buttons">
                    <a href="login.php" class="btn btn-primary">Войти</a>
                    <a href="register.php" class="btn btn-secondary">Регистрация</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="form-container">
                <h2 style="text-align: center; margin-bottom: 2rem; color: #333;">Вход в систему</h2>
                
                <?php if ($error): ?>
                    <div class="message error">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Войти</button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 1rem;">
                    <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
                    <p><small>Демо-аккаунт: admin@library.com / password123</small></p>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Библиотечная система. Все права защищены.</p>
        </div>
    </footer>
</body>
</html> 