<?php

declare(strict_types=1);

class Router {
    public static function route($url) {
        $parts = explode('/', $url);
        $controllerName = ucfirst($parts[0] ?? '') . 'Controller';

        if (empty($parts[0])) {
            $controllerName = 'AdminController';
        }

        $action = $parts[1] ?? 'index';

        $controllerPath = __DIR__ . "/../App/Controllers/$controllerName.php";
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controller = new $controllerName();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                echo "Action not found: $action";
            }
        } else {
            echo "Controller not found: $controllerName";
        }
    }
}
