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
            <li>
                <?php if (is_dir($currentPath . '/' . $file) && $file !== '.' && $file !== '..'): ?>
                    <!-- Ссылка для входа в папку -->
                    <a href="/admin/index?directory=<?= urlencode($directory . '/' . $file) ?>">
                        📁 <?= htmlspecialchars($file) ?>
                    </a>
                <?php else: ?>
                    <?= htmlspecialchars($file) ?>
                    <?php if (is_file($currentPath . '/' . $file)): ?>
                        <form method="POST" action="/admin/deleteFile">
                            <input type="hidden" name="file" value="<?= htmlspecialchars($file) ?>">
                            <button type="submit">Удалить</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    

<form method="POST" action="/index.php?route=admin/deleteFile">
    <input type="hidden" name="file" value="<?= htmlspecialchars($file) ?>">
    <input type="hidden" name="directory" value="<?= htmlspecialchars($directory) ?>">
    <button type="submit">Удалить</button>
</form>

<form method="POST" action="/index.php?route=admin/uploadFile" enctype="multipart/form-data">
    <input type="hidden" name="directory" value="<?= htmlspecialchars($directory) ?>">
    <input type="file" name="file">
    <button type="submit">Загрузить</button>
</form>

</body>
</html>
