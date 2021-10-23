<?php

header("Access-Control-Allow-Origin: *");
require __DIR__  . '/../vendor/autoload.php';

// Routing
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    foreach (include(__DIR__ . '/../config/routes.php') as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = ($httpMethod == 'POST') ? $_POST : $routeInfo[2];
        $controller = new $handler();
        call_user_func($controller, $vars);
        break;
}