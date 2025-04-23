<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Требуется авторизация.';
    exit;
} else {
    $validUser = 'admin';
    $validPassword = '12345678'; 

    if ($_SERVER['PHP_AUTH_USER'] !== $validUser || $_SERVER['PHP_AUTH_PW'] !== $validPassword) {
        header('HTTP/1.0 403 Forbidden');
        echo 'Неверные учетные данные.';
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
</head>
<body>
    <h1>Добро пожаловать в админ-панель!</h1>
    <!-- Здесь размещайте необходимые элементы управления -->
</body>
</html>
