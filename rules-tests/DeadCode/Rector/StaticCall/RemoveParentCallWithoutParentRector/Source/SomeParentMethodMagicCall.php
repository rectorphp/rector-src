<?php

namespace Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Source;

class SomeParentMethodMagicCall
{
    public function __call($name, $arguments)
    {
    }
}
