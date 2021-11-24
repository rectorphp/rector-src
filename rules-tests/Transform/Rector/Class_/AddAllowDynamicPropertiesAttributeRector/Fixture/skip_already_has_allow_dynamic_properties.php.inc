<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture;

#[\AllowDynamicProperties]
class AlreadyHasAttribute
{
    public string $someProperty = 'hello world';
}

?>
