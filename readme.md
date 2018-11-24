## Socialist is a realtime social project

Socialist using many libraries and packages that help us to make professional project
- [Laravel](https://laravel.com/).
- [Vuejs](https://vuejs.org/).
- [Pusher](https://pusher.com/).

there is many packages and libraries, you can see in composer.json and package.json


Socialist includes fully realtime functionality for any social media website
and also these function can be used for any purpose need realtime

## Features
- Realtime notifications when user comment on your post or when user send you new message request.
- Realtime comments when user comment on post you are watching, it will be updated at your screen.
- Realtime Messaging system, when user send you message you open, it will updated automatically at your screen.
  also, if you are not opening this room, it will do notification and number of unread messages will increase
  you can also see typing feature when other room user typing to you

## Installation Guide

1. Clone the project and replace it in htdocs directory
2. Run : ``` composer install ``` then run  ``` npm install ```
3. Rename .env.example to .env
4. Create database and insert it's credentials into .env
5. Create pusher account then get keys, and insert it into .env, with changing BROADCAST_DRIVER to pusher
6. Run ``` php artisan migrate -seed ```
7. Run ``` php artisan key:generate ```
8. Run ``` php artisan serve ```
9. If you want to run project into any url except 127.0.0.1:8000, go to resources/js/bootstrap.js and change base_url to your project base url then run ``` npm run watch ```
10. Visit 127.0.0.1:8000 or your project url, and enjoy realtime
11. Register new user or login, create post, message any one, comment on any post, and see the beauity of realtime