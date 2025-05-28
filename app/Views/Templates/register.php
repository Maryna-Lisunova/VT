<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Регистрация</title>
        <!-- Подключаем скрипт Google reCAPTCHA -->
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
            input[type="text"],
            input[type="email"],
            input[type="password"] {
                width: 100%;
                padding: 8px;
                box-sizing: border-box;
            }
            button {
                padding: 10px 20px;
                background: #28a745;
                border: none;
                color: #fff;
                border-radius: 4px;
                cursor: pointer;
            }
            button:hover {
                background: #218838;
            }
        </style>
    </head>
    <body>
        <h1 style="text-align:center;">Регистрация</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div>
                <label for="username">Имя пользователя:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="password">Пароль:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="confirm_password">Подтвердите пароль:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <div class="g-recaptcha" data-sitekey="ВАШ_SITE_KEY"></div>
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit">Зарегистрироваться</button>
            </div>
        </form>
    </body>
    </html>
    <?php
    exit;
}

require_once __DIR__ . '/../../../DataBase/DatabaseManager.php'; 

$username        = trim($_POST['username'] ?? '');
$email           = trim($_POST['email'] ?? '');
$password        = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$errors = [];

if (empty($username)) {
    $errors[] = "Введите имя пользователя.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Некорректный email.";
}
if (strlen($password) < 6) {
    $errors[] = "Пароль должен содержать не менее 6 символов.";
}
if ($password !== $confirmPassword) {
    $errors[] = "Пароли не совпадают.";
}

$captchaResponse = $_POST['g-recaptcha-response'] ?? '';
$secretKey = 'ВАШ_SECRET_KEY';
$verifyURL = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}";
$verifyResponse = file_get_contents($verifyURL);
$responseData = json_decode($verifyResponse);

if (!$responseData || !$responseData->success) {
    $errors[] = "Проверка капчи не пройдена.";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>{$error}</p>";
    }
    exit;
}

$db = DatabaseManager::getInstance()->getConnection();

$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "<p style='color:red;'>Пользователь с таким email уже существует.</p>";
    exit;
}
$stmt->close();

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $passwordHash);
if ($stmt->execute()) {
    echo "<p style='color:green;'>Регистрация прошла успешно!</p>";
} else {
    echo "<p style='color:red;'>Ошибка регистрации: " . $stmt->error . "</p>";
}
$stmt->close();
?>
