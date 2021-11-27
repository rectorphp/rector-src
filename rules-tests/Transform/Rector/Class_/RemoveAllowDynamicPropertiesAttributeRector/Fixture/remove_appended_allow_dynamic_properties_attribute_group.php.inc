<?php

namespace Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector\Fixture;

#[\AllowDynamicProperties]
#[\Immutable]
class RemoveAppendedAttributeGroup
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector\Fixture;

#[\Immutable]
class RemoveAppendedAttributeGroup
{
    public string $someProperty = 'hello world';
}

?>
