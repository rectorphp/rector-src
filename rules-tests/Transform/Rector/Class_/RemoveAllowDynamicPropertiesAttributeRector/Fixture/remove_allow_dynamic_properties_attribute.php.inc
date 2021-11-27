<?php

namespace Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector\Fixture;

#[\AllowDynamicProperties]
class WantsAttributeRemoved
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector\Fixture;

class WantsAttributeRemoved
{
    public string $someProperty = 'hello world';
}

?>
