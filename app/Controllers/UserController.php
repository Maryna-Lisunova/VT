<?php
declare(strict_types=1);

use Services\UserService;

class UserController {
    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
    }    

    public function index(): void {
    	session_start();
    	if (!isset($_SESSION['user'])) {
        	header('Location: /apps/my_project/login');
        	exit;
    	}

    	$data = $this->userService->getHomePageData();
    	extract($data);
    	require_once __DIR__ . '/../Views/Templates/user.php';
    	exit;
    }


    public function login(): void {
    session_start();

    if (isset($_SESSION['user'])) {
        header('Location: /apps/my_project/user');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

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
    } else {
        require_once __DIR__ . '/../Views/Templates/login.php';
        exit;
    }
}


    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        } else {
            require_once __DIR__ . '/../Views/Templates/register.php';
            exit;
        }
    }

    public function logout(): void {
        session_start();
        session_unset();
        session_destroy();
        setcookie("remember_me", "", time() - 3600, "/");
        header('Location: /apps/my_project/login');
        exit;
    }
}