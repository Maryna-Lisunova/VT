<?php

declare(strict_types=1);

class AdminController {

    private $allowedDirectories = ['', 'images', 'css', 'html', 'js'];

    public function __construct() {
        $this->authenticate();
    }

    /**
     * Проверка HTTP Basic Authentication.
     */
    private function authenticate(): void {
        $validUser = 'admin';
        $validPassword = '12345678'; 

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Админская панель"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Требуется авторизация';
            exit;
        } elseif ($_SERVER['PHP_AUTH_USER'] !== $validUser || $_SERVER['PHP_AUTH_PW'] !== $validPassword) {
            header('HTTP/1.0 403 Forbidden');
            echo 'Неверные данные авторизации';
            exit;
        }
    }

    /**
     * Метод для вывода файлового менеджера.
     */
    public function index(): void {
        $directory = $_GET['directory'] ?? ''; 
        $path = realpath(__DIR__ . '/../../Public/' . $directory);

        if ($this->isAllowed($path)) {
            $files = scandir($path); 
            $currentPath = $path; 
            require_once __DIR__ . '/../Views/Admin/admin.php'; 
        } else {
            echo "Доступ запрещен!";
        }
    }

    /**
     * Метод для удаления файла.
     */
    public function deleteFile(): void {
        $file = $_POST['file'] ?? '';
        $directory = $_POST['directory'] ?? '';
        echo "Удаляем файл: $file из папки: $directory<br>"; 

        $filePath = realpath(__DIR__ . '/../../Public/' . $directory . '/' . $file);

        if ($this->isAllowed($filePath) && file_exists($filePath)) {
            unlink($filePath);
            echo "Файл удалён.";
        } else {
            echo "Ошибка: файл не найден или доступ запрещён.";
        }
    }

    /**
     * Метод для загрузки файла.
     */
    public function uploadFile(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $directory = $_POST['directory'] ?? '';
            $targetDir = realpath(__DIR__ . '/../../Public/' . $directory);

            if ($this->isAllowed($targetDir)) {
                $targetFile = $targetDir . '/' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
                echo "Файл успешно загружен!";
            } else {
                echo "Ошибка: доступ запрещён.";
            }
        } else {
            echo "Ошибка загрузки файла.";
        }
    }

    /**
     * Проверяет, разрешён ли доступ к указанному пути.
     */
    private function isAllowed($path): bool {
        $publicRoot = realpath(__DIR__ . '/../../Public');
        foreach ($this->allowedDirectories as $dir) {
            $allowedPath = realpath($publicRoot . '/' . $dir);
            if ($allowedPath !== false && strpos(realpath($path), $allowedPath) === 0) {
                return true;
            }
        }
        return false;
    }
}
