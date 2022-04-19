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
