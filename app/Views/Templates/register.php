<?php
session_start();
require_once '/../../../DataBase/DatabaseManager.php'; 

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
if (!$responseData->success) {
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
