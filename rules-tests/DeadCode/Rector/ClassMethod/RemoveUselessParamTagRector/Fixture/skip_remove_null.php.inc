<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class SkipRemoveNull
{
    /**
     * @param null $a
     */
    public function foo(int $a = null)
    {
    }
}