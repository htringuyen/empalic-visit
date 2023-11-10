<?php
namespace Framework\Routing;
use Throwable;

class Router {

    private array $routes = [];
    private array $errorHandler = [];

    public function add(string $method, string $path, callable $handler): Route {
        $route = $this->routes[] = new Route($method, $path, $handler);
        return $route;
    }

    public function dispatch() {
        $requestMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";
        $requestPath = $_SERVER["REQUEST_URI"] ?? "/";

        $candidateRoutes = $this->findCandidateRoutes($requestPath);
        $hasPathMatched = ! empty($candidateRoutes);

        $candidateRoutes = self::filterByMethod($candidateRoutes, $requestMethod);
        $selectedRoute = self::selectByFirst($candidateRoutes);

        if ($selectedRoute) {
            try {
                return $selectedRoute->dispatch();
            }
            catch (Throwable $e) {
                return $this->dispatchError();
            }
        }

        if ($hasPathMatched) {
            return $this->dispatchNotAllowed();
        }

        return $this->dispatchNotFound();
    }

    private function findCandidateRoutes(string $path): array {
        return array_filter($this->routes, function($route) use ($path) {
            return $route->matchPath($path);
        });
    }

    private static function filterByMethod(array $routes, string $method): array {
        return array_filter($routes, function ($route) use ($method) {
            return $route->getMethod() === $method;
        });
    }

    private static function selectByFirst(array $routes): ?Route {
        if (!empty($routes)) {
            return array_values($routes)[0];
        }
        return null;
    }

    private function dispatchError() {
        $this->errorHandler[500] ??= fn() => "500 server error";
        return $this->errorHandler[500]();
    }

    private function dispatchNotAllowed() {
        $this->errorHandler[400] ??= fn() => "400 not allowed";
        return $this->errorHandler[400]();
    }

    private function dispatchNotFound() {
        $this->errorHandler[404] ??= fn() => "404 not found";
        return $this->errorHandler[404]();
    }

    public function redirect($path) {
        header("Location: {$path}", $replace=true, $code=301);
        exit;
    }


}