<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\Fixture;

use Rector\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector;

class SkipWithArgs
{
    public function test()
    {
        $obj = new RemoveGetClassGetParentClassNoArgsRector();
        echo get_class($obj);
        echo get_parent_class($obj);
    }
}
