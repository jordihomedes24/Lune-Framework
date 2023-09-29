<?php

namespace Lune\Tests\Routing;

use Lune\Routing\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public static function routesWithNoParameters()
    {
        return [
            ['/'],
            ['/test'],
            ['/test/nested'],
            ['test/another/nested/route']
        ];
    }

    /**
     * @dataProvider routesWithNoParameters
     */
    public function test_regex_with_no_parameters(string $uri)
    {
        $route = new Route($uri, fn () => "test");
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches("$uri/extra/path"));
        $this->assertFalse($route->matches("/extra/path/$uri"));
        $this->assertFalse($route->matches("/random/route"));
    }

    /**
     * @dataProvider routesWithNoParameters
     */
    public function test_regex_on_uri_that_ends_with_slash(string $uri)
    {
        $route = new Route($uri, fn () => "test");
        $this->assertTrue($route->matches("$uri/"));
    }

    public static function routesWithParameters()
    {
        return [
            [
                '/test/{test}',
                '/test/1',
                ['test' => 1],
            ],
            [
                '/users/{user}',
                '/users/2',
                ['user' => 2],
            ],
            [
                'test/{test}',
                'test/string',
                ['test' => "string"],
            ],
            [
                'test/nested/{route}',
                'test/nested/5',
                ['route' => 5],
            ],
            [
                'test/{param}/long/{test}/with/{multiple}/params',
                'test/hello/long/3/with/1120323/params',
                [
                    'param' => "hello",
                    'test' => 3,
                    'multiple' => 1120323
                ]
            ],
        ];
    }

    /**
     * @dataProvider routesWithParameters
     */
    public function test_regex_with_parameters(string $definition, string $uri)
    {
        $route = new Route($definition, fn () => "test");
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches("$uri/extra/path"));
        $this->assertFalse($route->matches("/extra/path/$uri"));
        $this->assertFalse($route->matches("/random/route"));
    }


    /**
     * @dataProvider routesWithParameters
     */
    public function test_parse_parameters(string $definition, string $uri, array $expectedParameters)
    {
        $route = new Route($definition, fn () => "test");
        $this->assertTrue($route->hasParameters());
        $this->assertEquals($expectedParameters, $route->parseParameters($uri));
    }
}
