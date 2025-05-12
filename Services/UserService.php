<?php
namespace Services;

class UserService {
    public function getHomePageData(): array {
        return [
            'title'      => 'Добро пожаловать на сайт!',
            'intro'      => 'Здесь вы найдёте всю необходимую информацию о нашем сервисе. Здесь будет представлен адвент календарь на рождественскую тему.',
            'btnCalendar'=> 'Открыть календарь',
            'btnAdmin'   => 'Перейти в админку'
        ];
    }
}
