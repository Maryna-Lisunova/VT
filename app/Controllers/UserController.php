<?php

declare(strict_types=1);

use Services\UserService;

class UserController {
    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function index(): void {
        $data = $this->userService->getHomePageData();
        extract($data);
        require_once __DIR__ . '/../Views/Templates/user.php';
        exit;
    }
}
