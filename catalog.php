<?php
session_start();

// Простые данные книг (в реальном проекте это было бы из базы данных)
$books = [
    [
        'id' => 1,
        'title' => 'Война и мир',
        'author' => 'Лев Толстой',
        'genre' => 'Классика',
        'year' => 1869,
        'available' => true
    ],
    [
        'id' => 2,
        'title' => 'Преступление и наказание',
        'author' => 'Федор Достоевский',
        'genre' => 'Классика',
        'year' => 1866,
        'available' => true
    ],
    [
        'id' => 3,
        'title' => 'Мастер и Маргарита',
        'author' => 'Михаил Булгаков',
        'genre' => 'Фантастика',
        'year' => 1967,
        'available' => false
    ],
    [
        'id' => 4,
        'title' => '1984',
        'author' => 'Джордж Оруэлл',
        'genre' => 'Антиутопия',
        'year' => 1949,
        'available' => true
    ],
    [
        'id' => 5,
        'title' => 'Гарри Поттер и философский камень',
        'author' => 'Дж. К. Роулинг',
        'genre' => 'Фэнтези',
        'year' => 1997,
        'available' => true
    ],
    [
        'id' => 6,
        'title' => 'Властелин колец',
        'author' => 'Дж. Р. Р. Толкин',
        'genre' => 'Фэнтези',
        'year' => 1954,
        'available' => true
    ]
];

// Фильтрация книг
$search = $_GET['search'] ?? '';
$genre_filter = $_GET['genre'] ?? '';
$available_filter = $_GET['available'] ?? '';

$filtered_books = $books;

if ($search) {
    $filtered_books = array_filter($filtered_books, function($book) use ($search) {
        return stripos($book['title'], $search) !== false || 
               stripos($book['author'], $search) !== false;
    });
}

if ($genre_filter) {
    $filtered_books = array_filter($filtered_books, function($book) use ($genre_filter) {
        return $book['genre'] === $genre_filter;
    });
}

if ($available_filter !== '') {
    $available = $available_filter === '1';
    $filtered_books = array_filter($filtered_books, function($book) use ($available) {
        return $book['available'] === $available;
    });
}

$genres = array_unique(array_column($books, 'genre'));
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог книг - Библиотечная система</title>
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
                    <li><a href="catalog.php" class="active">Каталог</a></li>
                    <li><a href="profile.php">Личный кабинет</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
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
            <h2 style="text-align: center; margin: 2rem 0; color: #333;">Каталог книг</h2>
            
            <!-- Фильтры -->
            <div class="catalog-filters">
                <form method="GET" action="catalog.php">
                    <div class="filters-grid">
                        <div class="form-group">
                            <label for="search">Поиск по названию или автору:</label>
                            <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Введите название или автора...">
                        </div>
                        
                        <div class="form-group">
                            <label for="genre">Жанр:</label>
                            <select id="genre" name="genre">
                                <option value="">Все жанры</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= $genre ?>" <?= $genre_filter === $genre ? 'selected' : '' ?>>
                                        <?= $genre ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="available">Доступность:</label>
                            <select id="available" name="available">
                                <option value="">Все книги</option>
                                <option value="1" <?= $available_filter === '1' ? 'selected' : '' ?>>Доступны</option>
                                <option value="0" <?= $available_filter === '0' ? 'selected' : '' ?>>Недоступны</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary">Применить фильтры</button>
                            <a href="catalog.php" class="btn btn-secondary">Сбросить</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Результаты поиска -->
            <div class="books-grid">
                <?php if (empty($filtered_books)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                        <p>Книги не найдены. Попробуйте изменить параметры поиска.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($filtered_books as $book): ?>
                        <div class="book-card">
                            <div class="book-cover">📚</div>
                            <h4><?= htmlspecialchars($book['title']) ?></h4>
                            <p><strong>Автор:</strong> <?= htmlspecialchars($book['author']) ?></p>
                            <p><strong>Год:</strong> <?= $book['year'] ?></p>
                            <span class="genre"><?= $book['genre'] ?></span>
                            <div style="margin-top: 1rem;">
                                <?php if ($book['available']): ?>
                                    <span style="color: green; font-weight: bold;">✓ Доступна</span>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <br><a href="order.php?book_id=<?= $book['id'] ?>" class="btn btn-primary" style="margin-top: 0.5rem;">Заказать</a>
                                    <?php else: ?>
                                        <br><small>Войдите, чтобы заказать</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color: red; font-weight: bold;">✗ Недоступна</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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