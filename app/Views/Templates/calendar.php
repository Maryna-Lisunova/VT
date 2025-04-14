<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<head>
    <title>MyAdventCalendar</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/xmas_crown.ico">
    <link rel="stylesheet" href="./styles.css">
    <meta name="msapplication-TileColor" content="#ff250d">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<header>
    <img src="images/crown_black_white.png" alt="Logo" class="header-logo">
    <h1>MyAdventCalendar</h1>
</header>

<div class="calendar">
    <?php foreach ($calendarData as $tile): ?>
        <div class="tile tile<?= $tile['day']; ?>" data-day="<?= $tile['day']; ?>" 
             data-type="<?= $tile['type']; ?>" 
             <?php if (!empty($tile['content'])): ?>data-content="<?= $tile['content']; ?>"<?php endif; ?>
             <?php if (!empty($tile['text'])): ?>data-text="<?= $tile['text']; ?>"<?php endif; ?>
             style="height: <?= $tile['height']; ?>;">
            <h3><?= $tile['day'] === 0 ? $tile['text'] : $tile['day']; ?></h3>
        </div>
    <?php endforeach; ?>
</div>

<footer>
    <address id="address">
        Page was created by<br>
        Maryna Lisunova<br>
        Student of group 351003<br>
        BSUIR, 2024<br>
    </address>
</footer>

<link rel="stylesheet" href="styles.css">
<script src="script.js"></script>
</body>
</html>