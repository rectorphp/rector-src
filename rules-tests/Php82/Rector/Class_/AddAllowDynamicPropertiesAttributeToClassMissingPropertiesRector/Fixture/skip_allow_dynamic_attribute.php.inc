<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

#[\AllowDynamicProperties]
class SkipAllowDynamicAttribute
{
    public function set()
    {
        $this->value = 5;

        $this->value = 'hey';
    }
}
