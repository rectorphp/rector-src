<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class SkipWithDescription
{
    /**
     * @return null some description
     */
    function foo()
    {
        return null;
    }
}
