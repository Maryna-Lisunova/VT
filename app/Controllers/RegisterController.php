<?php
declare(strict_types=1);

use Services\UserService;

class RegisterController {
    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../Views/Templates/register.php';
            exit;
        }
        
        $username        = trim($_POST['username'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $captchaResponse = $_POST['g-recaptcha-response'] ?? '';

        $result = $this->userService->register($username, $email, $password, $confirmPassword, $captchaResponse);

        if ($result['success']) {
            header('Location: /apps/my_project/login');
            exit;
        } else {
            $error = $result['message'];
            require_once __DIR__ . '/../Views/Templates/register.php';
            exit;
        }
    }
}
