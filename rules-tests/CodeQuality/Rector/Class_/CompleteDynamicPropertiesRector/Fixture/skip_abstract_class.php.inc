<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

abstract class SkipAbstractClass
{
    public function set()
    {
        $this->value = 5;
    }
}
