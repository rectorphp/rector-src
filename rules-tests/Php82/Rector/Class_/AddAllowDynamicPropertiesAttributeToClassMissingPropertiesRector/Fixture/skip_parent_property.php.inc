<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

class SomeChildClass extends SomeParentClassWithProperty
{
    public function set()
    {
        $this->value = 5;
    }
}

class SomeParentClassWithProperty
{
    public $value;
}
