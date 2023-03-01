## Hi
Привет, неизвестный пользователь!   
Это был мой первый пет проект на Laravel, обалдеть! 
Огромное количество рабочего функицонала! 
Да, спустя 4 года коммерческого опыта на laravel и go смотреть 
на этот код смешно, но пусть будет тут для истории.  
Да, я его обновил до последней версии laravel


https://user-images.githubusercontent.com/31564567/222020819-b1befa31-55ea-4518-b2db-1353c7e36884.mp4


## Как запустить
1. `cp .env.example .env`  
2. Измени `DOCKER_UID` и `DOCKER_USER` в .env на свои
3. Добавь в `/etc/hosts` crm.loc
4. `make up`
5. `make exec`
6. `composer install`
7. `php artisan migrate --seed`
8. `php artisan storage:link`
9. `php artisan key:generate`
10. Данные авторизации: `pavel@orendat.ru` `admin`
