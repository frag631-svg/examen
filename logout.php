<?php
session_start();

// Уничтожение сессии
session_destroy();

// Перенаправление на главную страницу
header('Location: index.php');
exit;
?> 