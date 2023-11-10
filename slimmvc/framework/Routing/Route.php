<?php
namespace Framework\Routing;

class Route {
    private string $method;
    private string $path;
    private $handler;

    public function __construct(string $method, string $path, callable $handler) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
    }

    public function matchPath(string $path): bool {
        return $this->path === $path;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function dispatch() {
        return call_user_func($this->handler);
    }
}