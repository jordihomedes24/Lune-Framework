<?php

require_once "../vendor/autoload.php";

use Lune\Http\HttpNotFoundException;
use Lune\Server\phpNativeServer;
use Lune\Http\Request;
use Lune\Routing\Router;

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
