<?php

require_once "../vendor/autoload.php";

use Lune\Http\HttpNotFoundException;
use Lune\Server\phpNativeServer;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Router;

$router = new Router();

$router->get('/test', function (Request $request) {
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");
    $response->setContent(json_encode(["message" => "GET OK"]));

    return $response;
});

$router->post('/test', function (Request $request) {
    return "POST OK";
});

$server = new phpNativeServer();
try { 
    $request = new Request($server);
    $route = $router->resolve($request);
    $action = $route->action();
    $response = $action($request);
    $server->sendResponse($response);
} catch (HttpNotFoundException $e) {
    $response = new Response();
    $response->setStatus(404);
    // $response->setContent("NOT FOUND");
    $response->setHeader("Content-Type", "text/plain");
    $server->sendResponse($response);
}
