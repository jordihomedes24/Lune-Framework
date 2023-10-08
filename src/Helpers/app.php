<?php

use Lune\App;
use Lune\Container\Container;

function app(string $class = App::class)
{
    return Container::resolve($class);
}
