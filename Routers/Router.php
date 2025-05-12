<?php
declare(strict_types=1);

class Router {
    public static function route($url) {
        $parts = explode('/', $url);
                
        $controllerName = ucfirst($parts[0]) . 'Controller';
                
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
