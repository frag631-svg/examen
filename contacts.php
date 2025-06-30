<?php
session_start();

$message = '';
$message_type = '';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message_text = trim($_POST['message'] ?? '');
    
    // Валидация
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Имя обязательно для заполнения';
    }
    
    if (empty($email)) {
        $errors[] = 'Email обязателен для заполнения';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный формат email';
    }
    
    if (empty($subject)) {
        $errors[] = 'Тема обязательна для заполнения';
    }
    
    if (empty($message_text)) {
        $errors[] = 'Сообщение обязательно для заполнения';
    }
    
    if (empty($errors)) {
        // Отправка email
        $to = 'admin@library.com'; // Email администратора
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $email_body = "
        <html>
        <head>
            <title>Сообщение с сайта библиотеки</title>
        </head>
        <body>
            <h2>Новое сообщение с сайта библиотеки</h2>
            <p><strong>Имя:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Тема:</strong> " . htmlspecialchars($subject) . "</p>
            <p><strong>Сообщение:</strong></p>
            <p>" . nl2br(htmlspecialchars($message_text)) . "</p>
            <hr>
            <p><small>Отправлено: " . date('Y-m-d H:i:s') . "</small></p>
        </body>
        </html>
        ";
        
        // Попытка отправки email
        if (mail($to, "Сообщение с сайта: " . $subject, $email_body, $headers)) {
            $message = 'Сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.';
            $message_type = 'success';
            
            // Очистка формы
            $name = $email = $subject = $message_text = '';
        } else {
            $message = 'Ошибка при отправке сообщения. Попробуйте позже или свяжитесь с нами по телефону.';
            $message_type = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - Библиотечная система</title>
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
                    <li><a href="contacts.php" class="active">Контакты</a></li>
                </ul>
                <div class="auth-buttons">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="btn btn-primary">Профиль</a>
                        <a href="logout.php" class="btn btn-secondary">Выйти</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Войти</a>
                        <a href="register.php" class="btn btn-secondary">Регистрация</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <h2 style="text-align: center; margin: 2rem 0; color: #333;">Контакты</h2>
            
            <!-- Контактная информация -->
            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Наши контакты</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                    <div>
                        <h4 style="color: #007bff; margin-bottom: 1rem;">📍 Адрес</h4>
                        <p>ул. Библиотечная, 123<br>
                        Москва, 123456<br>
                        Россия</p>
                    </div>
                    <div>
                        <h4 style="color: #007bff; margin-bottom: 1rem;">📞 Телефон</h4>
                        <p>+7 (495) 123-45-67<br>
                        +7 (495) 123-45-68</p>
                    </div>
                    <div>
                        <h4 style="color: #007bff; margin-bottom: 1rem;">✉️ Email</h4>
                        <p>info@library.com<br>
                        admin@library.com</p>
                    </div>
                    <div>
                        <h4 style="color: #007bff; margin-bottom: 1rem;">🕒 Часы работы</h4>
                        <p>Пн-Пт: 9:00 - 20:00<br>
                        Сб-Вс: 10:00 - 18:00</p>
                    </div>
                </div>
            </div>

            <!-- Форма обратной связи -->
            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Написать нам</h3>
                
                <?php if ($message): ?>
                    <div class="message <?= $message_type ?>">
                        <?= $message ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="contacts.php">
                    <div class="form-group">
                        <label for="name">Ваше имя *</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Тема сообщения *</label>
                        <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Сообщение *</label>
                        <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($message_text ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Отправить сообщение</button>
                    </div>
                </form>
            </div>

            <!-- Карта -->
            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Как нас найти</h3>
                <div style="background-color: #f8f9fa; padding: 2rem; text-align: center; border-radius: 5px;">
                    <p>📍 Здесь будет карта с нашим местоположением</p>
                    <p><small>В реальном проекте здесь будет встроена карта Google Maps или Яндекс.Карты</small></p>
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