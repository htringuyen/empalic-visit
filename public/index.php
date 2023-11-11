<?php
require_once __DIR__.'/../vendor/autoload.php';

$router = new \Slimmvc\Routing\Router();

$routes = require_once __DIR__.'/../app/routes.php';

$routes($router);

print $router->dispatch();