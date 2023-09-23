<?php

require_once "../vendor/autoload.php";

use Lune\HttpNotFoundException;
use Lune\Router;

$router = new Router();

$router->get('/test', function () {
    return "GET OK";
});

try {
    $action = $router->resolve();
    print($action());
} catch (HttpNotFoundException $e) {
    print("NOT FOUND");
    http_response_code(404);
}
