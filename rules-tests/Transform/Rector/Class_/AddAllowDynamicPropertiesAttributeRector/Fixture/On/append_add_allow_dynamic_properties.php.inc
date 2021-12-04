<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\On;

#[\Immutable]
class AppendAttribute
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\On;

#[\Immutable]
#[\AllowDynamicProperties]
class AppendAttribute
{
    public string $someProperty = 'hello world';
}

?>
