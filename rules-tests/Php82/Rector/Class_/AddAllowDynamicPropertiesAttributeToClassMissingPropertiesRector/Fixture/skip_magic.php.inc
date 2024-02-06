<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

class SkipMagic
{
    public function set()
    {
        $this->value = 5;
    }

    public function __set($key, $value)
    {
    }
}
