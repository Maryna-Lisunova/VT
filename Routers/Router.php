<?php
declare(strict_types=1);

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

class Router {
    public static function route($url) {
        $parts = explode('/', $url);
        
        $controllerSegment = strtolower($parts[0] ?? '');
        
        if ($controllerSegment === 'calendar') {
            $controllerName = 'CalendarController';
        } elseif ($controllerSegment === 'user') {
            $controllerName = 'UserController';
        } elseif (empty($controllerSegment)) {
            $controllerName = 'AdminController';  
        } else {
            $controllerName = ucfirst($parts[0]) . 'Controller';
        }
        
        $action = $parts[1] ?? 'index';
        
        $controllerPath = __DIR__ . "/../App/Controllers/{$controllerName}.php";
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            if (!class_exists($controllerName)) {
                exit("Класс контроллера '$controllerName' не найден.");
            }
            $controller = new $controllerName();
            if (!method_exists($controller, $action)) {
                exit("Метод '$action' в контроллере '$controllerName' не найден.");
            }
            $controller->$action();
        } else {
            exit("Контроллер не найден: $controllerName");
        }
    }
}

Router::route($requestUri);
