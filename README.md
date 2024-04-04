1. run composer dump-autoload
2. run php migrate.php
3. run php -S localhost:8000
4. change DB connect params in .env

#Credentials for login<br />
manager<br />
password<br />


#Про проект<br />

Проект використовує архітектуру MVC. Сторінки Login та Movies генеруються на сервері.
Для функціоналу створення, сортування, пошуку і тд використовується JS та JSON відповіді.

\index.php - точка входу, створення єкземпляру Kernel<br />
\app\Kernel.php - створення єкземплярів роутеру та реєстрація маршрутів<br />
\app\Router.php - додавання маршрутів. Виклик методу контролера<br />
\app\Services\DataBaseClient.php - сервіс для роботи з БД та побудови запитів<br />
\app\Http\Request.php - створення єкземпляру запиту<br />
\app\Http\Response.php - генерація Json та View відповідей<br />
\public\js - логіка JavaScript<br />
