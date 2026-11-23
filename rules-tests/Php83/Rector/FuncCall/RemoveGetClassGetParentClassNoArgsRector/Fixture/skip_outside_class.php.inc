<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\Fixture;

function skipOutsideClass()
{
    echo get_class();
    echo get_parent_class();
}
