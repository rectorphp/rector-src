<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

class SkipDefined
{
    private $value;
    public function set()
    {
        $this->value = 5;
    }
}
