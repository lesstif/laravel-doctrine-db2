# DB2 Feasibility Test Project with Laravel-Doctrine

## 1. How to Install

### 1.1. Clone & Install Dependencies

```sh
$ git clone git@github.com:appkr/laravel-doctrine-db2.git
$ cd laravel-doctrine-db2 
$ composer install
```

Create `.env` settings.

```sh
$ cp .env.example .env
$ php artisan key:generate
```

Fill out IBM DB2 connection information.

```sh
# .env

DB_CONNECTION=db2
DB_HOST=127.0.0.1
DB_PORT=50000
DB_DATABASE=sample
DB_USERNAME=db2inst1
DB_PASSWORD=password
```

### 1.2. Install `ibm_db2` PHP Extensions

Doctrine DBAL(DB Abstraction Layer) depends on `ibm_db2` extensions. I used [Homestead VM](https://laravel.com/docs/5.2/homestead).

```sh
vagrant@homestead $ sudo pecl install ibm_db2
# DB2 Installation Directory? : /opt/ibm/db2/V10.5/
vagrant@homestead $ sudo echo "extension=ibm_db2.so" > /etc/php/7.0/mods-available/ibm_db2.ini
vagrant@homestead $ sudo phpenmod ibm_db2
vagrant@homestead $ sudo service nginx restart
vagrant@homestead $ sudo service php7.0-fpm restart
```

### 1.3. Create Database & Schema

Start IBM DB2.

```sh
db2inst1@homestead $ db2start
```

Since `$ php artisan doctrine:schema:create` not working correctly against IBM DB2, we have to create tables MANUALLY.

```sh
db2inst1@homestead $ db2

db2 => connect to sample

db2 => create table users(id int not null primary key generated by default as identity(start with 1, increment by 1), name varchar(255), email varchar(255), password varchar(60), remember_token varchar(255), created_at timestamp, updated_at timestamp)

db2 => create table tasks(id int not null primary key generated by default as identity(start with 1, increment by 1), name varchar(255), created_at timestamp, updated_at timestamp)

db2 => create table password_resets(id int not null primary key generated by default as identity(start with 1, increment by 1), email varchar(255), token varchar(255), created_at timestamp)

db2 => create table scientists(id int not null primary key generated by default as identity(start with 1, increment by 1), firstname varchar(255), lastname varchar(255), created_at timestamp, updated_at timestamp)

db2 => create table theories(id int not null primary key generated by default as identity(start with 1, increment by 1), scientist_id int not null, title varchar(255), created_at timestamp, updated_at timestamp)
```

> **MySQL**
> 
> We assume mysql user is 'homestead'.
> 
> ```sh
> $ mysql -uroot -p
> mysql> CREATE DATABASE laravel_doctrine;
> mysql> GRANT ALTER, CREATE, INSERT, SELECT, DELETE, REFERENCES, UPDATE, DROP, EXECUTE, LOCK TABLES, INDEX ON laravel_doctrine.* TO 'homestead';
> mysql> FLUSH PRIVILEGES;
> mysql> quit
> ```
> 
> ```sh
> $ php artisan doctrine:schema:create
> ```

### 1.4. Test

Browse to http://localhost:8000

PHPUnit works against SQLite. 

```
$ vendor/bin/phpunit
```

### 1.5. Available Routes

```
+----------+-------------------------+-----------------------------------------------------------------+------------+
| Method   | URI                     | Action                                                          | Middleware |
+----------+-------------------------+-----------------------------------------------------------------+------------+
| GET|HEAD | /                       | Closure                                                         | web        |
| GET|HEAD | example                 | Closure                                                         | web        |
| GET|HEAD | home                    | App\Http\Controllers\HomeController@index                       | web,auth   |
| GET|HEAD | login                   | App\Http\Controllers\Auth\AuthController@showLoginForm          | web,guest  |
| POST     | login                   | App\Http\Controllers\Auth\AuthController@login                  | web,guest  |
| GET|HEAD | logout                  | App\Http\Controllers\Auth\AuthController@logout                 | web        |
| POST     | password/email          | App\Http\Controllers\Auth\PasswordController@sendResetLinkEmail | web,guest  |
| POST     | password/reset          | App\Http\Controllers\Auth\PasswordController@reset              | web,guest  |
| GET|HEAD | password/reset/{token?} | App\Http\Controllers\Auth\PasswordController@showResetForm      | web,guest  |
| GET|HEAD | register                | App\Http\Controllers\Auth\AuthController@showRegistrationForm   | web,guest  |
| POST     | register                | App\Http\Controllers\Auth\AuthController@register               | web,guest  |
| POST     | task                    | Closure                                                         | web        |
| GET|HEAD | task/{id}               | Closure                                                         | web        |
| DELETE   | task/{id}               | Closure                                                         | web        |
| GET|HEAD | task/{id}/update        | Closure                                                         | web        |
+----------+-------------------------+-----------------------------------------------------------------+------------+
```

## 2. Summary & Todo

Testing done against MySql/DB2. 

Laravel-Doctrine|MySql|IBM DB2
---|---|---
CRUD|Tested|Tested
User Registration|Tested|Tested
User Authentication|Tested|Tested
Password Reset|Tested|Tested
Authorization|Not tested|Not tested
Object Relationship|Not tested|Not tested

Next step is to test Authorization and Object Relation, and exploit Doctrine usage.

## 3. Basic DB2 Command

Start and Stop.

```sh
db2inst1@homestead $ db2start
db2inst1@homestead $ db2stop
```

Start DB2 Console Client.

```sh
db2inst1@homestead $ db2
# Ctrl+d or Ctrl+c to Exit
```

Equivalent to `mysql> show databases;`

```sh
db2 => list db directory
```

Equivalent to `mysql> use sample;`

```sh
db2 => connect to sample
```

Equivalent to `mysql> show tables;`

```sh
db2 => list tables
```

Equivalent to `mysql> describe tasks;`

```sh
db2 => describe table tasks
```