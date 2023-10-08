<?php

namespace Lune\Tests\View;

use Lune\View\LuneEngine;
use PHPUnit\Framework\TestCase;

class LuneEngineTest extends TestCase
{
    public function test_render_template_with_parameters()
    {
        $parameter1 = "Test 1";
        $parameter2 = 23;

        $expectedHTML = "
            <html>
                <body>
                    <h1>$parameter1</h1>
                    <h1>$parameter2</h1>
                </body>
            </html>
        ";

        $engine = new LuneEngine(__DIR__ . "/views");

        $content = $engine->render("test", compact("parameter1", "parameter2"), "layout");

        //We use regular expressions to delete the indentation of both html so we compare
        //just the content itself
        $this->assertEquals(
            preg_replace("/\s*/", "", $content),
            preg_replace("/\s*/", "", $expectedHTML)
        );
    }
}
