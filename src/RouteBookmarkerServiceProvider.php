<?php namespace Herlevsen\LaravelRouteBookmarker;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class RouteBookmarkerServiceProvider extends ServiceProvider {


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->publishes([
			__DIR__.'/config/route-bookmarker.php' => config_path('route-bookmarker.php'),
		]);

		$this->app->singleton(RouteBookmarkerCommand::class, function($app) {
			$config = $app['config'];

			return new RouteBookmarkerCommand($app[Router::class], $app['files'], $config['app']['url'], $config['route-bookmarker']['save_path'], $config['route-bookmarker']['filename']);
		});

		$this->commands(RouteBookmarkerCommand::class);
	}
}