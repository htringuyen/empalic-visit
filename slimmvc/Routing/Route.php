<?php
namespace Slimmvc\Routing;

class Route {
    private string $method;
    private string $path;
    private $handler;
    private array $requestParams = [];
    private array $pathVariables = [];
    private ?string $name;

    public function __construct(string $method, string $path, callable $handler, $name = null) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->name = $name;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getName(): string {
        return $this->name;
    }

    public function dispatch() {
        if (is_array($this->handler)) {

            [$class, $method] = $this->handler;

            if (is_string($class)) {
                $class = new $class;
            }

            return ctx()->call([$class, $method]);
        }

        return ctx()->call($this->handler);
    }

    public function matchUrl(string $url): bool {
        $requestPath = parse_url($url, PHP_URL_PATH);

        if (! $this->matchUrlPath($requestPath)) {
            return false;
        }

        $urlQuery = parse_url($url, PHP_URL_QUERY);

        $this->requestParams = $this->extractQueryParams($urlQuery);

        return true;
    }

    private function matchUrlPath(string $path): bool {
        if ($path === $this->getPath()) {
            return true;
        }

        $pattern = $this->normalisePath($this->path);

        $parameterNames = [];
        $pattern = preg_replace_callback("#{([^}]+)}/#", function(array $found) use ($parameterNames) {
            $parameterNames[] = rtrim($found[1]);
            if (str_ends_with($found[1], "?")) {
                return '([^/]*)(?:/?)';
            }
            else {
                return '([^/]+)/';
            }
        }, $pattern);

        if (!str_contains($pattern, "+") && !str_contains($pattern, "*")) {
            return false;
        }

        preg_match_all($pattern, $this->normalisePath($path), $matches);

        $parameterValues = [];

        if (count($matches[1]) > 0) {
            foreach($matches[1] as $value) {

                if ($value) {
                    $parameterValues[] = $value;
                }
                else {
                    $parameterValues[] = null;
                }
            }

            $emptyValues = array_fill(0, count($parameterNames), false);
            $parameterValues += $emptyValues;

            $this->pathVariables = array_combine($parameterNames, $parameterValues);

            return true;
        }
        return false;
    }

    private function extractQueryParams(string $urlQuery): array {
        $pattern = "/[&?]([^&?/]+)=([^&]*)/";

        preg_match_all($pattern, $urlQuery, $matches, PREG_SET_ORDER);

        $queryParams = [];

        foreach ($matches as $param) {
            $queryParams[$param[0]] = $param[1];
        }

        return $queryParams;
    }

    private function normalisePath($path): string {
        $path = trim($path, "/");

        $path = "/{$path}/";

        $path = preg_replace("/[\/]{2,}/", "/", $path);

        return $path;
    }
}














