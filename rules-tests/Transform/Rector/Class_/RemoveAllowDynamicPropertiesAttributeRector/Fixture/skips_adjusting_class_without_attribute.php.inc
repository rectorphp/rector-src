<?php

namespace Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector\Fixture;

#[\AllowDynamicProperty]
class DoesNothingForDifferentAttribute
{
    public string $someProperty = 'hello world';
}

?>
