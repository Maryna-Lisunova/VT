<?php

declare(strict_types=1);

use Services\AdminService;

class AdminController {

    private AdminService $adminService;

    public function __construct() {
	// без этой строчки он НЕ ВИДИТ нужный сервис и Я НЕ МОГУ ПОНЯТЬ ПОЧЕМУ
	// я перепроверила все нэймспэйсы и имена файлов и папок всё как надо, но нет он слепой
	require_once __DIR__ . '/../../Services/AdminService.php';
        $this->adminService = new AdminService();
        $this->adminService->authenticate();
    }

    public function index(): void {	
        $directory = $_GET['directory'] ?? '';
        $data = $this->adminService->getFileManagerData($directory);

        if ($data !== null) {
            extract($data); 
            require_once __DIR__ . '/../Views/Templates/admin.php';
        } else {
            echo "Доступ запрещён!";
        }
        exit;
    }

    public function deleteFile(): void {
        $file = $_POST['file'] ?? '';
        $directory = $_POST['directory'] ?? '';

        echo "Удаляем файл: $file из папки: $directory<br>";

        if ($this->adminService->deleteFile($file, $directory)) {
            echo "Файл удалён.";
        } else {
            echo "Ошибка: файл не найден или доступ запрещён.";
        }
    }

    public function uploadFile(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $directory = $_POST['directory'] ?? '';
            if ($this->adminService->uploadFile($_FILES['file'], $directory)) {
                echo "Файл успешно загружен!";
            } else {
                echo "Ошибка: доступ запрещён или произошла ошибка загрузки.";
            }
        } else {
            echo "Ошибка загрузки файла.";
        }
    }
}
