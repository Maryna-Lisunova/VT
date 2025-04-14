<?php

declare (strict_types = 1);

class FileManager {
    
// путь к папкам, к которым разрешен доступ
$allowedDirectories = [
    'images',
    'css',
    'templates',
    'js'
];

// проверкa доступа
function isAllowed($path) {
    global $allowedDirectories;
    foreach ($allowedDirectories as $dir) {
        if (strpos(realpath($path), realpath($dir)) === 0) {
            return true;
        }
    }
    return false;
}

// просмотр файлов
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $directory = $_GET['directory'] ?? '.';
    if (isAllowed($directory)) {
        echo json_encode(scandir($directory));
    } else {
        http_response_code(403);
        echo json_encode(["error" => "Access denied"]);
    }
}

// загрузка файла
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = $_POST['directory'];
    if (isAllowed($targetDir)) {
        $targetFile = $targetDir . '/' . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            echo json_encode(["success" => "File uploaded"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to upload file"]);
        }
    } else {
        http_response_code(403);
        echo json_encode(["error" => "Access denied"]);
    }
}

// удаление файла
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $file = $_DELETE['file'];
    if (isAllowed($file)) {
        if (unlink($file)) {
            echo json_encode(["success" => "File deleted"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete file"]);
        }
    } else {
        http_response_code(403);
        echo json_encode(["error" => "Access denied"]);
    }
}
}
