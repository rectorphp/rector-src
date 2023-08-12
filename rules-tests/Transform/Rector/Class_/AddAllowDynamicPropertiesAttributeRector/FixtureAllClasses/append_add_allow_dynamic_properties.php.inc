<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\FixtureAllClasses;

#[\Immutable]
final class AppendAddAllowDynamicProperties
{
    public string $someProperty = 'hello world';
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\FixtureAllClasses;

#[\Immutable]
#[\AllowDynamicProperties]
final class AppendAddAllowDynamicProperties
{
    public string $someProperty = 'hello world';
}

?>
