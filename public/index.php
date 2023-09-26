<?php

require_once "../vendor/autoload.php";

use Lune\HttpNotFoundException;
use Lune\phpNativeServer;
use Lune\Request;
use Lune\Route;
use Lune\Router;
use Lune\Server;

$router = new Router();

$router->get('/test', function () {
    return "GET OK";
});

$router->post('/test', function () {
    return "POST OK";
});

try {
    $route = $router->resolve(new Request(new phpNativeServer()));
    $action = $route->action();
    print($action());
} catch (HttpNotFoundException $e) {
    print("NOT FOUND");
    http_response_code(404);
}
