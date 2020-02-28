# instaClone
Instagram clone with Synfony 4 
# Requirements
PHP 7.2.9 or higher;
PDO-SQLite PHP extension enabled;
[yarn](https://classic.yarnpkg.com/en/) and [node](https://nodejs.org/en/)
and the usual Symfony application requirements.
# Installation
Download Symfony to install the symfony binary on your computer and run this command:
There's no need to configure anything to run the application. If you have installed Symfony, run this command and access the application in your browser at the given URL (https://localhost:8000 by default):
$ cd my_project/
$ yarn install
$ composer install
$ symfony server:start
$ yarn encore dev --watch
If you don't have the Symfony binary installed, run php -S localhost:8000 -t public/ to use the built-in PHP web server or configure a web server like Nginx or Apache to run the application.
