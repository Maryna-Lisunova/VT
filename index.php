<?php
spl_autoload_register(function ($className) {
    $baseDir = __DIR__ . '/';
    $file = $baseDir . str_replace('\\', '/', $className) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log("Autoloader error: Не найден файл для класса '{$className}' по пути: '{$file}'");
    }
});

$requestUri = ltrim($_SERVER['REQUEST_URI'], '/');

$basePath = 'apps/my_project';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
    $requestUri = ltrim($requestUri, '/');
}

if (preg_match("#^admin/?$#i", $requestUri)) {
    require_once __DIR__ . '/../Admin/index.php';
    exit;
}

require_once __DIR__ . '/Routers/Router.php';

Router::route($requestUri);
