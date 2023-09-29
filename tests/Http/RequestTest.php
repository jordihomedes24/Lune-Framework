<?php

namespace Lune\Tests\Http;

use Lune\Http\HttpMethod;
use Lune\Http\Request;
use Lune\Server\Server;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {
    public function test_request_returns_values_from_server_correctly() {
        $uri = "/test";
        $method = HttpMethod::POST;
        $data = [ "test" => "hey", "foo" => "bar" ];
        $query = [ "a" => "1", "b" => "2", "c" => "3" ];

        $server = $this->getMockBuilder(Server::class)->getMock();
        $server->method("requestUri")->willReturn($uri);
        $server->method("requestMethod")->willReturn($method);
        $server->method("postData")->willReturn($data);
        $server->method("queryParams")->willReturn($query);

        $request = new Request($server);

        $this->assertEquals($request->uri(), $uri);
        $this->assertEquals($request->method(), $method);
        $this->assertEquals($request->data(), $data);
        $this->assertEquals($request->query(), $query);
    }
}