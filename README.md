* Create a MySQL database
* Download composer
* Copy `.env.example` file to `.env` .
* Open the console and cd your project root directory
* Run `composer install or php composer.phar install`
* Run `php artisan key:generate`
* Run `php artisan migrate`
* Make Test Domain Like `central.test` 
* Change `APP_URL` to `http://central.test`in `.env`
* Run `php artisan db:Seed --class=TenantSeeder`

@import "bootstrap-icons/font/bootstrap-icons.css";
