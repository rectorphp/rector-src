<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

class SkipUndefinedParentClass extends NotAutoloaded
{
    public function set()
    {
        $this->value = 5;
    }
}
