<?php

namespace Lune\Tests\Routing;

use Closure;
use Lune\Http\HttpMethod;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private function createMockRequest(string $uri, HttpMethod $method)
    {
        return (new Request())
            ->setUri($uri)
            ->setMethod($method);
    }

    public function test_resolve_basic_route_with_callback_action()
    {
        $uri = "/test";
        $action = fn () => "THIS IS A TEST";
        $router = new Router();
        $router->get($uri, $action);

        $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::GET));
        $this->assertEquals($action, $route->action());
    }

    public function test_resolve_multiple_basic_route_with_callback_action()
    {
        $routes = [
            '/test' => fn () => "test",
            '/foo' => fn () => "foo",
            '/bar' => fn () => "bar",
            '/long/nested/route' => fn () => "long nested route",
        ];

        $router = new Router();

        foreach ($routes as $uri => $action) {
            $router->get($uri, $action);
        }

        foreach ($routes as $uri => $action) {
            $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::GET));
            ;
            $this->assertEquals($action, $route->action());
        }
    }

    public function test_resolve_multiple_basic_route_with_callback_action_for_different_http_methods()
    {
        $routes = [
            [HttpMethod::GET, "/test", fn () => "get"],
            [HttpMethod::POST, "/test", fn () => "post"],
            [HttpMethod::PUT, "/test", fn () => "put"],
            [HttpMethod::PATCH, "/test", fn () => "patch"],
            [HttpMethod::DELETE, "/test", fn () => "delete"],

            [HttpMethod::GET, "/random/get", fn () => "get"],
            [HttpMethod::POST, "/more/random/post", fn () => "post"],
            [HttpMethod::PUT, "/some/put/random", fn () => "put"],
            [HttpMethod::PATCH, "/hello/this/is/patch", fn () => "patch"],
            [HttpMethod::DELETE, "/a/delete", fn () => "delete"],
        ];

        $router = new Router();

        foreach ($routes as [$method, $uri, $action]) {
            $router->{strtolower($method->value)}($uri, $action);
        };

        foreach ($routes as [$method, $uri, $action]) {
            $route = $router->resolveRoute($this->createMockRequest($uri, $method));
            $this->assertEquals($action, $route->action());
        };
    }

    public function test_run_middlewares()
    {
        $middleware1 = new class () {
            public function handle(Request $request, Closure $next): Response
            {
                $response = $next($request);
                $response->setHeader('x-test-one', 'one');

                return $response;
            }
        };

        $middleware2 = new class () {
            public function handle(Request $request, Closure $next): Response
            {
                $response = $next($request);
                $response->setHeader('x-test-two', 'two');

                return $response;
            }
        };

        $router = new Router();
        $uri = '/test';
        $expectedResponse = Response::text("test");
        $router->get($uri, fn ($request) => $expectedResponse)
            ->setMiddlewares([$middleware1, $middleware2]);

        $response = $router->resolve($this->createMockRequest($uri, HttpMethod::GET));

        $this->assertEquals($response->headers("x-test-one"), "one");
        $this->assertEquals($response->headers("x-test-two"), "two");
        $this->assertEquals($response, $expectedResponse);
    }

    public function test_run_middlewares_stopping_stack()
    {
        $middleware1 = new class () {
            public function handle(Request $request, Closure $next): Response
            {
                $response = $next($request);
                $response->setHeader('x-test-one', 'one');

                return $response;
            }
        };

        $middleware2 = new class () {
            public function handle(Request $request, Closure $next): Response
            {
                return Response::text("I'M STOPPING THE STACK");
            }
        };

        $middleware3 = new class () {
            public function handle(Request $request, Closure $next): Response
            {
                $response = $next($request);
                $response->setHeader('x-test-two', 'two');

                return $response;
            }
        };

        $router = new Router();
        $uri = '/test';
        $expectedResponse = Response::text("test");
        $router->get($uri, fn ($request) => $expectedResponse)
            ->setMiddlewares([$middleware1, $middleware2, $middleware3]);

        $response = $router->resolve($this->createMockRequest($uri, HttpMethod::GET));

        $this->assertEquals($response->headers("x-test-one"), "one");
        $this->assertNull($response->headers("x-test-two"));
        $this->assertEquals($response, Response::text("I'M STOPPING THE STACK")->setHeader('x-test-one', 'one'));
    }
}
