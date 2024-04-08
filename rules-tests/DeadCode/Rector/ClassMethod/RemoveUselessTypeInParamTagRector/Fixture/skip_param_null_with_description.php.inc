<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

class SkipParamNullWithDescription
{
    /**
     * @param null $a this is description
     */
    public function foo(int $a = null)
    {

    }
}

