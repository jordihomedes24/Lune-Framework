<?php

namespace Lune\Tests\Http;

use Lune\Http\HttpMethod;
use Lune\Http\Request;
use Lune\Routing\Route;
use Lune\Server\Server;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function test_request_returns_values_from_server_correctly()
    {
        $uri = "/test";
        $method = HttpMethod::POST;
        $data = [ "test" => "hey", "foo" => "bar" ];
        $query = [ "a" => "1", "b" => "2", "c" => "3" ];

        $request = (new Request())
            ->setUri($uri)
            ->setMethod($method)
            ->setPostData($data)
            ->setQueryParameters($query);

        $this->assertEquals($request->uri(), $uri);
        $this->assertEquals($request->method(), $method);
        $this->assertEquals($request->data(), $data);
        $this->assertEquals($request->query(), $query);
    }

    public function test_data_returns_value_if_key_is_given()
    {
        $data = [ "test" => "hey", "foo" => "bar" ];

        $request = (new Request())->setPostData($data);

        $this->assertEquals($request->data("test"), $data["test"]);
        $this->assertEquals($request->data("foo"), $data["foo"]);
        $this->assertNull($request->data("not-exist"));
    }

    public function test_query_returns_value_if_key_is_given()
    {
        $query = [ "a" => "1", "b" => "2", "c" => "3" ];

        $request = (new Request())->setQueryParameters($query);

        $this->assertEquals($request->query("b"), $query["b"]);
        $this->assertEquals($request->query("a"), $query["a"]);
        $this->assertNull($request->data("not-exist"));
    }

    public function test_route_parameters_returns_value_if_key_is_given()
    {
        $route = new Route('/test/{param}/foo/{bar}', fn () => "test");
        $request = (new Request())
            ->setRoute($route)
            ->setUri('/test/1/foo/2');

        $this->assertEquals($request->routeParameters('param'), 1);
        $this->assertEquals($request->routeParameters('bar'), 2);
        $this->assertNull($request->routeParameters("doesn't exist"));
    }
}
