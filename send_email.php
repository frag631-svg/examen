<?php
/**
 * Скрипт для отправки электронных писем
 * Библиотечная система
 */

class EmailSender {
    private $smtp_host = 'localhost'; // SMTP сервер
    private $smtp_port = 25; // Порт SMTP
    private $from_email = 'noreply@library.com';
    private $from_name = 'Библиотечная система';
    
    /**
     * Отправка простого email через mail() функцию
     */
    public function sendSimpleEmail($to, $subject, $message, $headers = '') {
        // Базовые заголовки
        $default_headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
        $default_headers .= "Reply-To: {$this->from_email}\r\n";
        $default_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $default_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        
        // Объединение заголовков
        $final_headers = $default_headers . $headers;
        
        // Отправка email
        return mail($to, $subject, $message, $final_headers);
    }
    
    /**
     * Отправка email с использованием PHPMailer (если установлен)
     */
    public function sendWithPHPMailer($to, $subject, $message, $attachments = []) {
        // Проверка наличия PHPMailer
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            throw new Exception('PHPMailer не установлен');
        }
        
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Настройки сервера
            $mail->isSMTP();
            $mail->Host = $this->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = 'your_username';
            $mail->Password = 'your_password';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->smtp_port;
            
            // Получатели
            $mail->setFrom($this->from_email, $this->from_name);
            $mail->addAddress($to);
            
            // Контент
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            
            // Вложения
            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment['path'], $attachment['name']);
            }
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Ошибка отправки email: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    /**
     * Отправка уведомления о заказе книги
     */
    public function sendOrderNotification($user_email, $user_name, $book_title, $order_date, $return_date) {
        $subject = 'Заказ книги подтвержден';
        
        $message = "
        <html>
        <head>
            <title>Заказ книги</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f8f9fa; }
                .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
                .book-info { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #007bff; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📚 Библиотечная система</h1>
                </div>
                <div class='content'>
                    <h2>Здравствуйте, {$user_name}!</h2>
                    <p>Ваш заказ книги был успешно оформлен.</p>
                    
                    <div class='book-info'>
                        <h3>Информация о заказе:</h3>
                        <p><strong>Книга:</strong> {$book_title}</p>
                        <p><strong>Дата заказа:</strong> {$order_date}</p>
                        <p><strong>Дата возврата:</strong> {$return_date}</p>
                    </div>
                    
                    <p>Пожалуйста, не забудьте вернуть книгу в указанный срок.</p>
                    <p>Спасибо за использование нашей библиотеки!</p>
                </div>
                <div class='footer'>
                    <p>Это автоматическое сообщение, не отвечайте на него.</p>
                    <p>&copy; 2024 Библиотечная система</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->sendSimpleEmail($user_email, $subject, $message);
    }
    
    /**
     * Отправка напоминания о возврате книги
     */
    public function sendReturnReminder($user_email, $user_name, $book_title, $return_date) {
        $subject = 'Напоминание о возврате книги';
        
        $message = "
        <html>
        <head>
            <title>Напоминание о возврате</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #ffc107; color: #333; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f8f9fa; }
                .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
                .warning { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 15px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>⚠️ Напоминание</h1>
                </div>
                <div class='content'>
                    <h2>Здравствуйте, {$user_name}!</h2>
                    
                    <div class='warning'>
                        <h3>Срок возврата книги истекает!</h3>
                        <p><strong>Книга:</strong> {$book_title}</p>
                        <p><strong>Дата возврата:</strong> {$return_date}</p>
                    </div>
                    
                    <p>Пожалуйста, верните книгу в библиотеку в указанный срок, чтобы избежать штрафов.</p>
                    <p>Спасибо за понимание!</p>
                </div>
                <div class='footer'>
                    <p>Это автоматическое сообщение, не отвечайте на него.</p>
                    <p>&copy; 2024 Библиотечная система</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->sendSimpleEmail($user_email, $subject, $message);
    }
    
    /**
     * Отправка сообщения администратору
     */
    public function sendAdminNotification($admin_email, $user_name, $user_email, $subject, $message_text) {
        $subject_admin = "Новое сообщение от пользователя: {$subject}";
        
        $message = "
        <html>
        <head>
            <title>Сообщение от пользователя</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #28a745; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f8f9fa; }
                .user-info { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #28a745; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📧 Новое сообщение</h1>
                </div>
                <div class='content'>
                    <div class='user-info'>
                        <h3>Информация о пользователе:</h3>
                        <p><strong>Имя:</strong> {$user_name}</p>
                        <p><strong>Email:</strong> {$user_email}</p>
                        <p><strong>Тема:</strong> {$subject}</p>
                    </div>
                    
                    <h3>Сообщение:</h3>
                    <p>" . nl2br(htmlspecialchars($message_text)) . "</p>
                    
                    <p><small>Отправлено: " . date('Y-m-d H:i:s') . "</small></p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->sendSimpleEmail($admin_email, $subject_admin, $message);
    }
}

// Пример использования
if (isset($_POST['action'])) {
    $emailSender = new EmailSender();
    
    switch ($_POST['action']) {
        case 'order_notification':
            $result = $emailSender->sendOrderNotification(
                $_POST['user_email'],
                $_POST['user_name'],
                $_POST['book_title'],
                $_POST['order_date'],
                $_POST['return_date']
            );
            echo $result ? 'Email отправлен успешно' : 'Ошибка отправки email';
            break;
            
        case 'return_reminder':
            $result = $emailSender->sendReturnReminder(
                $_POST['user_email'],
                $_POST['user_name'],
                $_POST['book_title'],
                $_POST['return_date']
            );
            echo $result ? 'Напоминание отправлено' : 'Ошибка отправки напоминания';
            break;
            
        case 'admin_notification':
            $result = $emailSender->sendAdminNotification(
                $_POST['admin_email'],
                $_POST['user_name'],
                $_POST['user_email'],
                $_POST['subject'],
                $_POST['message']
            );
            echo $result ? 'Уведомление администратору отправлено' : 'Ошибка отправки уведомления';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отправка Email - Библиотечная система</title>
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
            <h2 style="text-align: center; margin: 2rem 0; color: #333;">Тестирование отправки Email</h2>
            
            <div class="form-container">
                <h3>Отправка уведомления о заказе</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="order_notification">
                    <div class="form-group">
                        <label>Email пользователя:</label>
                        <input type="email" name="user_email" value="test@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Имя пользователя:</label>
                        <input type="text" name="user_name" value="Тестовый пользователь" required>
                    </div>
                    <div class="form-group">
                        <label>Название книги:</label>
                        <input type="text" name="book_title" value="Война и мир" required>
                    </div>
                    <div class="form-group">
                        <label>Дата заказа:</label>
                        <input type="date" name="order_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Дата возврата:</label>
                        <input type="date" name="return_date" value="<?= date('Y-m-d', strtotime('+30 days')) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить уведомление</button>
                </form>
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