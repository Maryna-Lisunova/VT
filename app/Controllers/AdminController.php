<?php

declare(strict_types=1);

class AdminController {

    private $allowedDirectories = ['', 'images', 'css', 'html', 'js'];
  
public function index() {
    $directory = $_GET['directory'] ?? ''; // Получаем параметр из URL
    $path = realpath(__DIR__ . '/../../Public/' . $directory);

    if ($this->isAllowed($path)) {
        $files = scandir($path); // Сканируем содержимое папки
        $currentPath = $path; // Передаём текущий путь в представление
        require_once __DIR__ . '/../Views/Admin/admin.php'; // Подключаем файл представления
    } else {
        echo "Доступ запрещен!";
    }
}


    public function deleteFile() {
    $file = $_POST['file'] ?? '';
    $directory = $_POST['directory'] ?? '';
    echo "Удаляем файл: $file из папки: $directory<br>"; // Отладка
    
    $filePath = realpath(__DIR__ . '/../../Public/' . $directory . '/' . $file);

    if ($this->isAllowed($filePath) && file_exists($filePath)) {
        unlink($filePath);
        echo "Файл удалён.";
    } else {
        echo "Ошибка: файл не найден или доступ запрещён.";
    }
}

public function uploadFile() {
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

}


   
private function isAllowed($path) {
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
