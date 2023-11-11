<?php

namespace Slimmvc\Routing;
use Slimmvc\Routing\Exception\RouteException;
use Throwable;

class Router {

    private array $routes = [];
    private array $errorHandler = [];
    private Route $current;

    public function add(string $method, string $path, callable $handler): Route {
        $route = $this->routes[] = new Route($method, $path, $handler);
        return $route;
    }

    public function getCurrent(): Route {
        return $this->current;
    }

    public function buildUrl(string $routeName, array $pathVariables, array $requestParams): string {
        foreach ($this->routes as $route) {
            if ($route->getName() === $routeName) {
                $finds = [];
                $replaces = [];

                foreach ($pathVariables as $key => $value) {
                    $finds[] = "{{$key}}";
                    $replaces[] = $value;

                    $finds[] = "{{$key}?}";
                    $replaces[] = $value;
                }

                $path = str_replace($finds, $replaces, $route->getPath());
                $path = preg_replace('#{[^}]+}#', '', $path);

                $urlQuery = "?";
                foreach ($requestParams as $key => $value) {
                    $urlQuery = $urlQuery."{$key}={$value}&";
                }

                $urlQuery = rtrim($urlQuery, "&");

                return $path.$urlQuery;
            }
        }

        throw new RouteException("No route that has the name {$routeName}");
    }

    public function dispatch() {
        $requestMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";
        $url = $_SERVER["REQUEST_URI"] ?? "/";

        $candidateRoutes = $this->findCandidateRoutes($url);
        $hasPathMatched = ! empty($candidateRoutes);

        $candidateRoutes = self::filterByMethod($candidateRoutes, $requestMethod);
        $selectedRoute = self::selectByFirst($candidateRoutes);

        if ($selectedRoute) {
            try {
                $this->current = $selectedRoute;
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

    private function findCandidateRoutes(string $url): array {

        return array_filter($this->routes, function(Route $route) use ($url) {

            return $route->matchUrl($url);
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
}