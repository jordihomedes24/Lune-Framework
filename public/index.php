<?php

require_once "../vendor/autoload.php";

use Lune\App;
use Lune\Container\Container;
use Lune\Http\HttpNotFoundException;
use Lune\Server\phpNativeServer;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Router;

$app = App::bootstrap();

Container::singleton(Router::class);

$app->router->get('/test/{param}', function (Request $request) {        
    return Response::json($request->routeParameters());
});

$app->router->post('/test', function (Request $request) {
    return Response::text("POST OK");
});

$app->router->get('/redirect', function (Request $request) {
    return Response::redirect("/test");
});

$app->run();
