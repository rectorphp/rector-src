<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class SkipDifferentTagValue
{
    /**
     * @return mixed
     */
    function foo()
    {
        return null;
    }
}
