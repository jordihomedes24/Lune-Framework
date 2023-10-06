<?php

namespace Lune;

use Lune\Container\Container;
use Lune\Http\HttpNotFoundException;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Router;
use Lune\Server\phpNativeServer;
use Lune\Server\Server;

class App
{
    public Router $router;

    public Request $request;

    public Server $server;

    public static function bootstrap()
    {
        $app = Container::singleton(App::class);
        $app->router = new Router();
        $app->server = new phpNativeServer();
        $app->request = $app->server->getRequest();

        return $app;
    }

    public function run()
    {
        try {
            $response = $this->router->resolve($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $response = Response::text("NOT FOUND")->setStatus(404);
            $this->server->sendResponse($response);
        }
    }
}
