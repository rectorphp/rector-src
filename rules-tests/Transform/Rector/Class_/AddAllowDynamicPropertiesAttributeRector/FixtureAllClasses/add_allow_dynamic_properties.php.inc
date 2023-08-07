<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\FixtureAllClasses;

final class AddAllowDynamicProperties
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\FixtureAllClasses;

#[\AllowDynamicProperties]
final class AddAllowDynamicProperties
{
    public string $someProperty = 'hello world';
}

?>
