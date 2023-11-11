<?php
namespace Slimmvc\DependencyInjection;

use Dotenv\Dotenv;
use Slimmvc\Http\Response;
use Slimmvc\Routing\Router;

class ApplicationContext extends Container {
    private static $instance;

    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }


    private function __construct() {

    }

    private function __clone() {

    }

    private function loadEnvVars(string $basePath): void {
        $dotenv = Dotenv::createImmutable($basePath);
        $dotenv->load();
    }

    private function bindProviders(string $basePath) {

    }

    private function dispatch(string $basePath): Response {
        if (! $this->has(Router::class)) {
            $router = new Router();

            $SEP = DIRECTORY_SEPARATOR;

            $loadRoutes = require $basePath.$SEP."app".$SEP."routes.php";
            $loadRoutes($router);

            $this->bind(Router::class, fn() => $router);
        }

        $response = $this->resolve(Router::class)->dispatch();

        if (!$response instanceof Response) {
            $response = $this->resolve("response")->content($response);
        }

        return $response;
    }
}
























