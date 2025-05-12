<?php
namespace Services;

class AdminService {
    private array $allowedDirectories = ['', 'images', 'css', 'html', 'js'];

    public function authenticate(): void {
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

    public function isAllowed(string $path): bool {
        // Публичная директория – ../Public относительно папки Services
        $publicRoot = realpath(__DIR__ . '/../Public');
        foreach ($this->allowedDirectories as $dir) {
            $allowedPath = realpath($publicRoot . '/' . $dir);
            if ($allowedPath !== false && strpos(realpath($path), $allowedPath) === 0) {
                return true;
            }
        }
        return false;
    }

    public function getFileManagerData(string $directory = ''): ?array {
        $publicRoot = realpath(__DIR__ . '/../Public');
        $path = realpath($publicRoot . '/' . $directory);
        if ($path !== false && $this->isAllowed($path)) {
            $files = scandir($path);
            return [
                'files'       => $files,
                'currentPath' => $path,
            ];
        }
        return null;
    }

    public function deleteFile(string $file, string $directory): bool {
        $publicRoot = realpath(__DIR__ . '/../Public');
        $filePath = realpath($publicRoot . '/' . $directory . '/' . $file);
        if ($filePath !== false && $this->isAllowed($filePath) && file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public function uploadFile(array $fileData, string $directory): bool {
        $publicRoot = realpath(__DIR__ . '/../Public');
        $targetDir = realpath($publicRoot . '/' . $directory);
        if ($targetDir !== false && $this->isAllowed($targetDir)) {
            $targetFile = $targetDir . '/' . basename($fileData['name']);
            return move_uploaded_file($fileData['tmp_name'], $targetFile);
        }
        return false;
    }
}
