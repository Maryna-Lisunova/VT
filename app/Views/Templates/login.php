<?php
session_start();
require_once 'DataBase/DatabaseManager.php';

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);  

$captchaResponse = $_POST['g-recaptcha-response'] ?? '';
$secretKey = 'ВАШ_SECRET_KEY';
$verifyURL = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}";
$verifyResponse = file_get_contents($verifyURL);
$responseData = json_decode($verifyResponse);
if (!$responseData->success) {
    echo "<p style='color:red;'>Проверка капчи не пройдена.</p>";
    exit;
}

$db = DatabaseManager::getInstance()->getConnection();

$stmt = $db->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
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
    setcookie("remember_me", $userId, time() + (86400 * 30), "/");
}

echo "<p style='color:green;'>Авторизация прошла успешно!</p>";
$stmt->close();
?>
