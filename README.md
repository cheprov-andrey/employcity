Установка проекта
- Выполнить composer install
- Создать файл .env.local
- Настроить подключение к БД (пример в .env.loc)
- Выполнить команду php bin/console doctrine:database:create для создания БД
- выполнить команду php bin/console doctrine:migrations:migrate для создания таблицы
- Выполнить команду php bin/console app:parser-news rbc Ответ должен быть "Ok"
- Перейти на главную страницу там будет список новостей