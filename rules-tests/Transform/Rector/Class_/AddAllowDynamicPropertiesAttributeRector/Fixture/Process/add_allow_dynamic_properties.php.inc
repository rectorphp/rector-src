<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\Process;

final class AddAllowDynamicProperties
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\Process;

#[\AllowDynamicProperties]
final class AddAllowDynamicProperties
{
    public string $someProperty = 'hello world';
}

?>
