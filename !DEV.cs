$ composer create-project --prefer-dist laravel/laravel:7.* .

$ composer require laravel/ui:^2.4 // auth part1
$ php artisan ui vue --auth         // auth part2

$ npm install
$ npm audit fix --force
$ npm install --save-dev cross-env // windows


$ php artisan make:seeder LoaderSeeder

// bootstrap

$ npm install bootstrap@latest @popperjs/core --save-dev
$ npm audit fix --force


Error: Cannot find module 'webpack/lib/rules/DescriptionDataMatcherRulePlugin'
// Solution: I have to manually update the vue-loader:^15.9.8 in the package JSON to solve the error. // A bilo u menja ^15.9.7
$ npm install


--------------------

### dimka windows install ###
composer install
npm install
npm audit fix --force

php artisan session:table
php artisan cache:table
php artisan storage:link

.env APP_KEY u foruma i game dolzni sovpadatj

sozdal dir/ storage/uploads/avatars // navernoe nado avatar 777 i vipolnit shortcut to public

### dimka linux install ###
composer install
npm install
npm audit fix --force

php artisan storage:link

sudo chown -R www-data.www-data /var/www/forum.vsmuta.com/storage
sudo chown -R www-data.www-data /var/www/forum.vsmuta.com/bootstrap/cache

sudo chmod -R 775 storage
sudo chmod -R ugo+rw storage 

.env
    new project key
    new app name

