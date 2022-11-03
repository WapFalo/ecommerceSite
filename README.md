# ecommerceSite

How to initialize the project in your local :
- "git clone" or "git pull" the project
- create '.env.local' file where you will have to define your local database informations
- run "composer install" (install 'composer' if you don't already have it)

If you already config your database localy (if not good luck installing mysql) these are few steps to config ecoblues database :
- "php bin/console doctrine:database:create" -> create the database as you defined it in .env.local
- "php bin/console doctrine:migrations:migrate" -> to run migrations that are not passed
