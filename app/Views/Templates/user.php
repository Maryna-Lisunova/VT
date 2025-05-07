<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title ?? 'Главная страница'; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/apps/my_project/Public/css/styles_for_user.css">
</head>
<body>
  <div class="container">
    <h1><?php echo $title ?? 'Добро пожаловать на наш сайт!'; ?></h1>
    <p><?php echo $intro ?? 'Это вводный текст, который описывает назначение сайта или приветствует пользователя.'; ?></p>
    <div class="button-group">
      <a href="/apps/my_project/calendar" class="btn"><?php echo $btnCalendar ?? 'Открыть календарь'; ?></a>
      <a href="/apps/my_project/admin" class="btn"><?php echo $btnAdmin ?? 'Перейти в админку'; ?></a>
    </div>
  </div>
</body>
</html>

