# Laravel Route Bookmarker

## What is it?
Laravel Route Bookmarker is a package, for Laravel 5, that helps you bookmark all your application routes in a simple and easy way.
It works by hooking into Laravels Router component and scanning through all routes , that uses the HTTP GET method.
It then creates an html file with some javascript, that will open each route in a new tab.
The major browsers then has an option, that allows you to save a bookmark for every open tab in the window.

## How to?
First add `herlevsen/laravel-route-bookmarker` to your composer.json file, and do a composer update. Add the service provider, `Herlevsen\LaravelRouteBookmarker\RouteBookmarkerServiceProvider`, to your providers array. Next publish the config file, by running `php artisan vendor:publish` in the terminal. Optionally you can now change the path to the generated html file in config/route-bookmarker.php. If you've done everything right, you should now have a new artisan command. Run "php artisan route-bookmarker:generate", to generate the html file at the specified path.

## Trouble
If you have any trouble, feel free to create an issue here on Github. You are also very welcome to make suggestions for the package or contribute your own code.