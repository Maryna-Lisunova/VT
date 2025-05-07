<?php

declare(strict_types=1);

class UserController {
    public function index(): void {

        $data = [
            'title'      => 'Добро пожаловать на сайт!',
            'intro'      => 'Здесь вы найдёте всю необходимую информацию о нашем сервисе. Здесь будет представлен адвент календарь на рождественскую тему.',
            'btnCalendar'=> 'Открыть календарь',
            'btnAdmin'   => 'Перейти в админку'
        ];
        
        extract($data);
        
        require_once __DIR__ . '/../Views/Templates/user.php';
        exit;
    }
}
