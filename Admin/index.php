<?php
declare(strict_types=1);

$config = require_once __DIR__ . '/../Config/config.php';

$validUser = $config['admin_user'];
$validPassword = $config['admin_password'];

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Админская панель"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Требуется авторизация';
    exit;
} else {
    if ($_SERVER['PHP_AUTH_USER'] !== $validUser || $_SERVER['PHP_AUTH_PW'] !== $validPassword) {
        header('HTTP/1.0 403 Forbidden');
        echo 'Неверные данные авторизации';
        exit;
    }
}

require_once __DIR__ . '/../App/Controllers/AdminController.php';
$adminController = new AdminController();
$adminController->index();
