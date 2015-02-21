<?php namespace Herlevsen\LaravelRouteBookmarker;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class RouteBookmarkerCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'route-bookmarker:generate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate html file that will help you bookmark all your routes';

	/**
	 * @var Route[]
	 */
	private $routes;

	/**
	 * @var Filesystem
	 */
	private $filesystem;
	/**
	 * @var string
	 */
	private $appUrl;
	/**
	 * @var
	 */
	private $savePath;
	/**
	 * @var
	 */
	private $saveName;

	public function __construct(Router $router, Filesystem $filesystem, $appUrl, $savePath, $saveName)
	{
		parent::__construct();

		$this->routes = $router->getRoutes();
		$this->filesystem = $filesystem;
		$this->appUrl = $appUrl;
		$this->filePath = $savePath . '/' . $saveName . '.html';
	}

	public function fire()
	{
		if (count($this->routes) == 0)
		{
			$this->error("Your application has no routes.");
			return;
		}

		$this->generate();
	}

	public function generate()
	{
		$tableRows = "";
		$jsRoutes = "";

		foreach($this->routes as $route)
		{
			// Skip route if it has no GET urls
			if( !$this->hasGetMethod($route) || $this->hasParameters($route) ) continue;

			$tableRows .= $this->generateTableRow($route);
			$jsRoutes  .= $this->generateJsString($route);
		}

		// Remove trailing comma
		$jsRoutes = rtrim($jsRoutes, ',');

		$stub = $this->filesystem->get(__DIR__ . "/route-bookmarker.stub");
		$stub = str_replace("{{urls_table}}", $tableRows, $stub);
		$stub = str_replace("{{urls}}", $jsRoutes, $stub);

		$this->filesystem->put($this->filePath, $stub);

		$this->info("The route bookmarker html file has been created in: " . $this->filePath . ".");
	}

	private function generateTableRow(Route $route)
	{
		$tableRow = "";

		$tableRow .= '<tr>';
		$tableRow .= '<td>' . $route->getName() . '</td>';
		$tableRow .= '<td><a href="' . $this->appUrl . '/' . $route->getUri() . '" target="_blank">' . $this->appUrl . '/' . $route->getPath() . '</a></td>';
		$tableRow .= '</tr>';

		return $tableRow;
	}

	private function generateJsString(Route $route)
	{
		$jsRoutes = "'" . $this->appUrl . '/' . $route->getUri() . "',";

		return $jsRoutes;
	}

	private function hasGetMethod(Route $route)
	{
		return in_array( 'GET', $route->getMethods() );
	}

	private function hasParameters(Route $route)
	{
		return count($route->parameterNames()) !== 0;
	}

}