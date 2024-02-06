<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

class AddicatedClass
{
    use PropertyInjection;

    public function set()
    {
        $this->value = 5;
    }
}

trait PropertyInjection
{
    private $value;
}
