<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Простые данные пользователя (в реальном проекте из БД)
$user = [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['user_name'] ?? 'Пользователь',
    'email' => $_SESSION['user_email'] ?? 'user@example.com',
    'registration_date' => '2024-01-15'
];

// Простые данные заказов (в реальном проекте из БД)
$orders = [
    [
        'id' => 1,
        'book_title' => 'Война и мир',
        'order_date' => '2024-01-20',
        'return_date' => '2024-02-20',
        'status' => 'active'
    ],
    [
        'id' => 2,
        'book_title' => 'Преступление и наказание',
        'order_date' => '2024-01-10',
        'return_date' => '2024-02-10',
        'status' => 'returned'
    ]
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Библиотечная система</title>
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
                    <li><a href="profile.php" class="active">Личный кабинет</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                </ul>
                <div class="auth-buttons">
                    <a href="profile.php" class="btn btn-primary">Профиль</a>
                    <a href="logout.php" class="btn btn-secondary">Выйти</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <h2 style="text-align: center; margin: 2rem 0; color: #333;">Личный кабинет</h2>
            
            <!-- Информация о пользователе -->
            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Информация о пользователе</h3>
                <div class="form-group">
                    <label><strong>Имя:</strong></label>
                    <p><?= htmlspecialchars($user['name']) ?></p>
                </div>
                <div class="form-group">
                    <label><strong>Email:</strong></label>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div class="form-group">
                    <label><strong>Дата регистрации:</strong></label>
                    <p><?= $user['registration_date'] ?></p>
                </div>
                <div class="form-group">
                    <a href="edit_profile.php" class="btn btn-primary">Редактировать профиль</a>
                </div>
            </div>

            <!-- Заказы пользователя -->
            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Мои заказы</h3>
                
                <?php if (empty($orders)): ?>
                    <p>У вас пока нет заказов. <a href="catalog.php">Перейти к каталогу</a></p>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                            <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Книга</th>
                                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Дата заказа</th>
                                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Дата возврата</th>
                                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Статус</th>
                                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr style="border-bottom: 1px solid #ddd;">
                                        <td style="padding: 1rem;"><?= htmlspecialchars($order['book_title']) ?></td>
                                        <td style="padding: 1rem;"><?= $order['order_date'] ?></td>
                                        <td style="padding: 1rem;"><?= $order['return_date'] ?></td>
                                        <td style="padding: 1rem;">
                                            <?php if ($order['status'] === 'active'): ?>
                                                <span style="color: green; font-weight: bold;">Активен</span>
                                            <?php else: ?>
                                                <span style="color: blue; font-weight: bold;">Возвращен</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 1rem;">
                                            <?php if ($order['status'] === 'active'): ?>
                                                <a href="return_book.php?order_id=<?= $order['id'] ?>" class="btn btn-secondary" style="font-size: 0.8rem;">Вернуть</a>
                                            <?php else: ?>
                                                <span style="color: #666;">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Статистика -->
            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Статистика</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div style="text-align: center; padding: 1rem; background-color: #f8f9fa; border-radius: 5px;">
                        <h4 style="color: #007bff; font-size: 2rem; margin-bottom: 0.5rem;"><?= count($orders) ?></h4>
                        <p>Всего заказов</p>
                    </div>
                    <div style="text-align: center; padding: 1rem; background-color: #f8f9fa; border-radius: 5px;">
                        <h4 style="color: green; font-size: 2rem; margin-bottom: 0.5rem;">
                            <?= count(array_filter($orders, function($order) { return $order['status'] === 'active'; })) ?>
                        </h4>
                        <p>Активных заказов</p>
                    </div>
                    <div style="text-align: center; padding: 1rem; background-color: #f8f9fa; border-radius: 5px;">
                        <h4 style="color: blue; font-size: 2rem; margin-bottom: 0.5rem;">
                            <?= count(array_filter($orders, function($order) { return $order['status'] === 'returned'; })) ?>
                        </h4>
                        <p>Возвращенных книг</p>
                    </div>
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