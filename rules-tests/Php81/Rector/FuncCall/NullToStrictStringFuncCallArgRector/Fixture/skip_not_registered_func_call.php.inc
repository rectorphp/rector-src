<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipNotRegisteredFuncCall
{
    public function run()
    {
        get_headers('http://www.google.com', false, null);
    }
}
