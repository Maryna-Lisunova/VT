<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в аккаунт</title>
    <!-- Подключаем скрипт reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 20px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 4px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        form div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background: #007BFF;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Вход в аккаунт</h2>
    <!-- Отключаем автозаполнение для всей формы -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
        <div>
            <label for="email">Email:</label>
            <!-- отключаем автозаполнение для поля email -->
            <input type="email" id="email" name="email" required autocomplete="off">
        </div>
        <div>
            <label for="password">Пароль:</label>
            <!-- для поля пароля часто используют значение 'new-password', чтобы предотвратить автозаполнение -->
            <input type="password" id="password" name="password" required autocomplete="new-password">
        </div>
        <div>
            <label for="remember">
                <input type="checkbox" id="remember" name="remember"> Запомнить меня
            </label>
        </div>
        <div class="g-recaptcha" data-sitekey="ВАШ_SITE_KEY"></div>
        <div style="text-align: center;">     
            <button type="submit">Войти</button>           
        </div>
    </form>
    <p>Еще не зарегистрированы? <a href="/apps/my_project/register">Регистрация</a></p>
</body>
</html>
    <?php
    exit;
}

require_once __DIR__ . '/../../../DataBase/DatabaseManager.php'; 

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

$captchaResponse = $_POST['g-recaptcha-response'] ?? '';
$secretKey       = 'ВАШ_SECRET_KEY';
$verifyURL       = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}";
$verifyResponse  = file_get_contents($verifyURL);
$responseData    = json_decode($verifyResponse);

if (!$responseData || !$responseData->success) {
    echo "<p style='color:red;'>Проверка капчи не пройдена.</p>";
    exit;
}

$db = DatabaseManager::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
if (!$stmt) {
    echo "<p style='color:red;'>Ошибка подготовки запроса.</p>";
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<p style='color:red;'>Пользователь не найден.</p>";
    exit;
}

$stmt->bind_result($userId, $username, $passwordHash);
$stmt->fetch();

if (!password_verify($password, $passwordHash)) {
    echo "<p style='color:red;'>Неверный пароль.</p>";
    exit;
}

$_SESSION['user'] = [
    'id'       => $userId,
    'username' => $username,
    'email'    => $email
];

if ($remember) {
    setcookie("remember_me", $userId, time() + (86400 * 30), "/");  // 30 дней
}

echo "<p style='color:green;'>Авторизация прошла успешно!</p>";
$stmt->close();
?>
