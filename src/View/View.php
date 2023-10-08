<?php

namespace Lune\View;

interface View
{
    public function render(string $view, array $params = [], string $layout = null): string;
}
