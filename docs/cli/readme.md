#postgresql
- to run: psql -U postgres (windows) | sudo -u postgres psql (linux)
- to create new database: CREATE DATABASE db_name;
- to show all databases: \l
- to show data tables: \dt
- to create new user: CREATE USER desta WITH PASSWORD 'password';
- to show users list: \du
- to grant user with specified database: GRANT ALL PRIVILEGES ON DATABASE db_name TO desta;
- to change user's password: ALTER USER user_name WITH PASSWORD 'newPassword';
- to restart psql system: net stop postgresql-x64-16 & net start postgresql-x64-16
- to give another user access to database:
        ALTER DATABASE db_name OWNER TO user_name;
        GRANT ALL PRIVILEGES ON DATABASE laravel_api TO desta;
        GRANT ALL PRIVILEGES ON SCHEMA public TO desta;
        ALTER SCHEMA public OWNER TO desta;
- to drop database: DROP DATABASE name_database;

#laravel
- to migrate table with no deleted tables data: php artisan migrate
- to migrate table with down all the tables data : php artisan migrate:fresh
- to generate controller: php artisan make:controller controllerName
- to generate request: php artisan make:request requestName
- to generate response: php artisan make:response responseName
- to generate model: php artisan make:model modelName
- to generate new migration table: php artisan make:migration create_name_table --create=name_table
- to run: php artisan serve