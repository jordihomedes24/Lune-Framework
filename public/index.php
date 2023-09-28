<?php

require_once "../vendor/autoload.php";

use Lune\Http\HttpNotFoundException;
use Lune\Server\phpNativeServer;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Router;

$router = new Router();

$router->get('/test', function (Request $request) {        
    return Response::text("GET OK");
});

$router->post('/test', function (Request $request) {
    return Response::text("POST OK");
});

$router->get('/redirect', function (Request $request) {
    return Response::redirect("/test");
});

$server = new phpNativeServer();
try { 
    $request = new Request($server);
    $route = $router->resolve($request);
    $action = $route->action();
    $response = $action($request);
    $server->sendResponse($response);
} catch (HttpNotFoundException $e) {
    $response = Response::text("NOT FOUND")->setStatus(404);
    $server->sendResponse($response);
}
