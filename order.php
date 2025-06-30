<?php
session_start();
$book = isset($_GET['book']) ? htmlspecialchars($_GET['book']) : '';
$is_auth = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказ книги</title>
    <link rel="stylesheet" href="css/style.css">
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
            </nav>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <h2>Заказ книги</h2>
            <?php if ($book): ?>
                <p>Вы хотите заказать книгу: <strong><?php echo $book; ?></strong></p>
                <?php if ($is_auth): ?>
                    <form method="post" action="">
                        <input type="hidden" name="book" value="<?php echo $book; ?>">
                        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
                    </form>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        echo '<p class="success">Спасибо! Ваш заказ на книгу <strong>' . $book . '</strong> оформлен.</p>';
                    }
                    ?>
                <?php else: ?>
                    <p>Для оформления заказа необходимо <a href="login.php">войти</a> или <a href="register.php">зарегистрироваться</a>.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Книга не выбрана. Вернитесь в <a href="catalog.php">каталог</a>.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Библиотечная система. Все права защищены.</p>
        </div>
    </footer>
</body>
</html> 