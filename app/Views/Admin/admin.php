<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Файловый менеджер</title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <h1>Файловый менеджер</h1>
    <h2>Текущая папка: <?= htmlspecialchars($currentPath) ?></h2>
    
    <ul>
        <?php foreach ($files as $file): ?>
            <?php if ($file !== '.' && $file !== '..'): ?>
                <li>
                    <?php $fullPath = $currentPath . '/' . $file; ?>
                    <?php if (is_dir($fullPath)): ?>
                        <!-- Ссылка для перехода в папку -->
                        <a href="?route=admin/index&directory=<?= urlencode(($directory ? $directory . '/' : '') . $file) ?>">
                            📁 <?= htmlspecialchars($file) ?>
                        </a>
                    <?php else: ?>
                        <?= htmlspecialchars($file) ?>
                        <?php if (is_file($fullPath)): ?>
                            <!-- Форма для удаления файла -->
                            <form method="POST" action="?route=admin/deleteFile" style="display:inline;">
                                <input type="hidden" name="file" value="<?= htmlspecialchars($file) ?>">
                                <input type="hidden" name="directory" value="<?= htmlspecialchars($directory) ?>">
                                <button type="submit">Удалить</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <hr>

    <h3>Загрузить файл:</h3>
    <form method="POST" action="?route=admin/uploadFile" enctype="multipart/form-data">
        <input type="hidden" name="directory" value="<?= htmlspecialchars($directory) ?>">
        <input type="file" name="file">
        <button type="submit">Загрузить</button>
    </form>
</body>
</html>
