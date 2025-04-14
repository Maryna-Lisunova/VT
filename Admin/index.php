<?php

// Активируем строгую типизацию
declare(strict_types=1);

// Подключение роутера
require_once __DIR__ . "/Routers/Router.php";

// Получаем маршрут из URL
$url = $_GET['route'] ?? 'admin/index'; // По умолчанию admin/index

// Вызов маршрута
Router::route($url);

