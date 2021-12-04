<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\On;

class WantsAttribute
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\On;

#[\AllowDynamicProperties]
class WantsAttribute
{
    public string $someProperty = 'hello world';
}

?>
