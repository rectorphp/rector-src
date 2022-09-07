<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipFirstClassCallable
{
    public function run()
    {
        array_map(trim(...), [' ']);
    }
}
