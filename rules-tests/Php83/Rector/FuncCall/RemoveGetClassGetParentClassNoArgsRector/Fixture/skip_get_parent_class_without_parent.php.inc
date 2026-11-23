<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\Fixture;

class SkipGetParentClassWithoutParent
{
    public function test()
    {
        echo get_parent_class();
    }
}
