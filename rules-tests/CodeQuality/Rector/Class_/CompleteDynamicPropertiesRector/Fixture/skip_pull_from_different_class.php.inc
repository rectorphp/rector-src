<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Source\DifferentClass;

class SkipPullFromDifferentClass
{
    public function run()
    {
        $obj = new DifferentClass();
        echo $obj->property;
    }
}
