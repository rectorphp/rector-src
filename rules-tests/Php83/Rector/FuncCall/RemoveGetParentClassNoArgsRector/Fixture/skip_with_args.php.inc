<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetParentClassNoArgsRector\Fixture;

use Rector\Php83\Rector\FuncCall\RemoveGetClassNoArgsRector;

class SkipWithArgs
{
    public function test()
    {
        $obj = new RemoveGetClassNoArgsRector();
        echo get_parent_class($obj);
    }
}
