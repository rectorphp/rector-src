<?php

namespace Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector\Fixture;

#[\AllowDynamicProperties, \Immutable]
class RemoveAppendedAttribute
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector\Fixture;

#[\Immutable]
class RemoveAppendedAttribute
{
    public string $someProperty = 'hello world';
}

?>
