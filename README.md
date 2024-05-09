* Create a MySQL database
* Download composer https://getcomposer.org/download/
* Copy `.env.example` file to `.env` .
* Open the console and cd your project root directory
* Run `composer install or php composer.phar install`
* Run `php artisan key:generate`
* Run `php artisan migrate`
* Make Test Domain Like `central.test` 
* Change `APP_URL` to `http://central.test`in `.env`
* Run `php artisan db:Seed --class=TenantSeeder`

Now Two New Databases Created `tenant_foo`,`tenant_bar` and two subdomain also created `foo.central.test` ,`bar.central.test` `foo.central.test` connected to `tenant_foo` and  `bar.central.test` connected to `tenant_bar`
