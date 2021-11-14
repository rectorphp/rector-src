<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture;

class AnotherObject
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture;

#[\AllowDynamicProperties]
class AnotherObject
{
    public string $someProperty = 'hello world';
}

?>
