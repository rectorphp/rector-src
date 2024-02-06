<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

use Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Source\DifferentClass;

class SkipPullFromDifferentClass
{
    public function run()
    {
        $obj = new DifferentClass();
        echo $obj->property;
    }
}
