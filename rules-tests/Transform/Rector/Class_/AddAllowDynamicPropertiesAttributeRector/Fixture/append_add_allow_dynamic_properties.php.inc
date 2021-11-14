<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture;

#[\Immutable]
class SomeObject
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture;

#[\Immutable]
#[\AllowDynamicProperties]
class SomeObject
{
    public string $someProperty = 'hello world';
}

?>
