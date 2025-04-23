<?php
declare(strict_types=1);

if (isset($_GET['route']) && strpos($_GET['route'], 'admin') === 0) {
    require_once __DIR__ . '/Admin/index.php';
    exit;
}

require_once __DIR__ . '/Routers/Router.php';
$url = $_GET['route'] ?? '';
Router::route($url);
