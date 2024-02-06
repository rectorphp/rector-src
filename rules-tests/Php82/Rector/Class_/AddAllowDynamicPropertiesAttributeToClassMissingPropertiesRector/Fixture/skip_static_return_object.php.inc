<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

use Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Source\StaticFactory;

final class SkipStaticReturnObject
{
    public function getCurrentTimestamp()
    {
        return StaticFactory::now()->timestamp;
    }
}
