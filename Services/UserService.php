<?php
namespace Services;

use Repository\UserRepository; 

class UserService {
    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function getHomePageData(): array {
        return [
            'title'       => 'Добро пожаловать на сайт!',
            'intro'       => 'Здесь вы найдёте всю необходимую информацию о нашем сервисе. Здесь будет представлен адвент календарь на рождественскую тему.',
            'btnCalendar' => 'Открыть календарь',
            'btnAdmin'    => 'Перейти в админку'
        ];
    }

    public function login(string $email, string $password, bool $remember): array {
        $user = $this->userRepository->getByEmail($email);
        if (!$user) {
            return ['success' => false, 'message' => 'Пользователь не найден'];
        }
        if (!password_verify($password, $user->passwordHash)) {
            return ['success' => false, 'message' => 'Неверный пароль'];
        }
        $_SESSION['user'] = [
            'id'       => $user->id,
            'username' => $user->username,
            'email'    => $user->email
        ];
        if ($remember) {
            setcookie("remember_me", $user->id, time() + (86400 * 30), "/");
        }
        return ['success' => true, 'message' => 'Авторизация успешна'];
    }

    public function register(string $username, string $email, string $password, string $confirmPassword, string $captchaResponse): array {
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            return ['success' => false, 'message' => 'Все поля обязательны для заполнения'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Некорректный email'];
        }
        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Пароли не совпадают'];
        }
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Пароль должен быть не менее 6 символов'];
        }
        if (empty($captchaResponse)) {
            return ['success' => false, 'message' => 'Проверка капчи не пройдена'];
        }

        $existingUser = $this->userRepository->getByEmail($email);
        if ($existingUser !== null) {
            return ['success' => false, 'message' => 'Пользователь с данным email уже существует'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $newUser = new \App\Models\User(null, $username, $email, $passwordHash);
        $userId = $this->userRepository->create($newUser);
        if ($userId > 0) {
            return ['success' => true, 'message' => 'Регистрация успешна'];
        }
        return ['success' => false, 'message' => 'Ошибка регистрации. Попробуйте позже'];
    }
}
