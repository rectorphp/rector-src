<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

use Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Source\MagicParent;

class SkipMagicParent extends MagicParent
{
    public function set()
    {
        $this->value = 5;
    }
}
