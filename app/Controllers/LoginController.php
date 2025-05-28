<?php
declare(strict_types=1);

use Services\UserService;

class LoginController {
    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(): void {
        if (isset($_SESSION['user'])) {
            header('Location: /apps/my_project/user');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../Views/Templates/login.php';
            exit;
        }
        
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

        $result = $this->userService->login($email, $password, $remember);

        if ($result['success']) {
            $_SESSION['user'] = $result['user'];
            header('Location: /apps/my_project/user');
            exit;
        } else {
            $error = $result['message'];
            require_once __DIR__ . '/../Views/Templates/login.php';
            exit;
        }
    }
}
