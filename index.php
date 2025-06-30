<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Библиотечная система - Главная</title>
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
                    <li><a href="index.php" class="active">Главная</a></li>
                    <li><a href="catalog.php">Каталог</a></li>
                    <li><a href="profile.php">Личный кабинет</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                </ul>
                <div class="auth-buttons">
                    <?php
                    session_start();
                    if (isset($_SESSION['user_id'])) {
                        echo '<a href="profile.php" class="btn btn-primary">Профиль</a>';
                        echo '<a href="logout.php" class="btn btn-secondary">Выйти</a>';
                    } else {
                        echo '<a href="login.php" class="btn btn-primary">Войти</a>';
                        echo '<a href="register.php" class="btn btn-secondary">Регистрация</a>';
                    }
                    ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="main">
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h2>Добро пожаловать в нашу библиотеку</h2>
                    <p>Откройте для себя мир знаний с нашей обширной коллекцией книг</p>
                    <a href="catalog.php" class="btn btn-large">Перейти к каталогу</a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h3>Почему выбирают нас?</h3>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📖</div>
                        <h4>Большая коллекция</h4>
                        <p>Более 10,000 книг различных жанров и направлений</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h4>Быстрый поиск</h4>
                        <p>Удобная система поиска и фильтрации книг</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h4>Онлайн доступ</h4>
                        <p>Заказывайте книги онлайн в любое время</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="new-books">
            <div class="container">
                <h3>Новые поступления</h3>
                <div class="books-grid">
                    <?php
                    $books = [
                        [
                            'title' => 'Война и мир',
                            'author' => 'Лев Толстой',
                            'genre' => 'Классика',
                        ],
                        [
                            'title' => 'Преступление и наказание',
                            'author' => 'Федор Достоевский',
                            'genre' => 'Классика',
                        ],
                        [
                            'title' => 'Мастер и Маргарита',
                            'author' => 'Михаил Булгаков',
                            'genre' => 'Фантастика',
                        ],
                        [
                            'title' => '1984',
                            'author' => 'Джордж Оруэлл',
                            'genre' => 'Антиутопия',
                        ],
                    ];
                    ?>
                    <?php foreach ($books as $book): ?>
                    <div class="book-card">
                        <div class="book-cover">📚</div>
                        <h4><?= htmlspecialchars($book['title']) ?></h4>
                        <p><?= htmlspecialchars($book['author']) ?></p>
                        <span class="genre"><?= htmlspecialchars($book['genre']) ?></span>
                        <a href="order.php?book=<?= urlencode($book['title']) ?>" class="btn btn-order">Заказать</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Библиотечная система. Все права защищены.</p>
        </div>
    </footer>
</body>
</html> 