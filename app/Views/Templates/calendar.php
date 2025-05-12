<?php

if (!function_exists('asset')) {
    function asset(string $path): string {
        return '/apps/my_project/Public/' . ltrim($path, '/');
    }
}
?>
<!DOCTYPE html>
<html lang="<?= isset($lang) ? htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') : 'en-US'; ?>" dir="<?= isset($dir) ? htmlspecialchars($dir, ENT_QUOTES, 'UTF-8') : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'MyAdventCalendar'; ?></title>
    <link rel="icon" type="image/x-icon" href="<?= asset('images/xmas_crown.ico'); ?>">
    <link rel="stylesheet" href="<?= asset('css/styles.css'); ?>">
    <meta name="msapplication-TileColor" content="#ff250d">
    <meta name="theme-color" content="#ffffff">
    <?= isset($head) ? $head : ''; ?>
</head>
<body style="background-image: url('<?= asset($bgImage ?? 'images/christmas_paper1280.jpg'); ?>'); 
             background-size: cover; background-repeat: no-repeat; background-position: center;">
<header>
    <img src="<?= asset('images/crown_black_white.png'); ?>" alt="Logo" class="header-logo">
    <h1><?= isset($calendarTitle) ? htmlspecialchars($calendarTitle, ENT_QUOTES, 'UTF-8') : 'MyAdventCalendar'; ?></h1>
</header>

<div class="calendar">
    <!-- выводим контент календаря, считанный из внешнего файла -->
    <?= $calendarContent; ?>
</div>

<footer>
    <address id="address">
        Page was created by<br>
        Maryna Lisunova<br>
        Student of group 351003<br>
        BSUIR, 2024<br>
    </address>
</footer>

<script src="<?= asset('js/script.js'); ?>"></script>
<?= isset($scripts) ? $scripts : ''; ?>
</body>
</html>
