<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassNoArgsRector\Fixture;

use Rector\Php83\Rector\FuncCall\RemoveGetClassNoArgsRector;

class SkipWithArgs
{
    public function test()
    {
        $obj = new RemoveGetClassNoArgsRector();
        echo get_class($obj);
    }
}
