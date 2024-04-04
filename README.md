1. run composer dump-autoload
2. run php migrate.php
3. run php -S localhost:8000
4. change DB connect params in .env

#Credentials for login
manager
password


#Про проект
Проект використовує архітектуру MVC. Сторінки Login та Movies генеруються на сервері.
Для функціоналу створення, сортування, пошуку і тд використовується JS та JSON відповіді.

\index.php - точка входу, створення єкземпляру Kernel
\app\Kernel.php - створення єкземплярів роутеру та реєстрація маршрутів
\app\Router.php - додавання маршрутів. Виклик методу контролера
\app\Services\DataBaseClient.php - сервіс для роботи з БД та побудови запитів
\app\Http\Request.php - створення єкземпляру запиту
\app\Http\Response.php - генерація Json та View відповідей
\public\js - логіка JavaScript
