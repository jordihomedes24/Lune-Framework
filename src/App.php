<?php

namespace Lune;

use Lune\Container\Container;
use Lune\Http\HttpNotFoundException;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Router;
use Lune\Server\phpNativeServer;
use Lune\Server\Server;
use Lune\Validation\Exceptions\ValidationException;
use Lune\Validation\Rule;
use Lune\View\LuneEngine;
use Lune\View\View;
use Throwable;

class App
{
    public Router $router;

    public Request $request;

    public Server $server;

    public View $viewEngine;

    public static function bootstrap()
    {
        $app = Container::singleton(App::class);
        $app->router = new Router();
        $app->server = new phpNativeServer();
        $app->request = $app->server->getRequest();
        $app->viewEngine = new LuneEngine(__DIR__ . "/../views");
        Rule::loadDefaultRules();

        return $app;
    }

    public function run()
    {
        try {
            $response = $this->router->resolve($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $this->abort(Response::text("NOT FOUND")->setStatus(404));
        } catch (ValidationException $e) {
            $this->abort(json($e->errors())->setStatus(422));
        } catch (Throwable $e) {
            $response = json([
                "error" => $e::class,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ]);

            $this->abort($response->setStatus(500));
        }
    }

    public function abort(Response $response)
    {
        $this->server->sendResponse($response);
    }
}
