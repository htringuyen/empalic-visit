<?php
use Framework\Routing\Router;

return function (Router $router) {
    $router->add("GET", "/", fn() => "base service: hello world");
    $router->add("GET", "/old-home", fn() => $router->redirect("/"));
    $router->add("GET", "/has-server-error", fn() => throw new Exception());
    $router->add("GET", "/greeting", fn() => "hello user");
};